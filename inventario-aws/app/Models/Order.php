<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Services\SmsService;

class Order extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = ['customer_id', 'order_date', 'total_amount', 'status', 'notification_sent'];

    protected $dates = ['order_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details')->withPivot('quantity', 'unit_price')->withTimestamps();
    }

    public function toSearchableArray()
    {
        $this->loadMissing(['customer', 'products']);

        $productsSummary = $this->products->map(function ($product) {
            return [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'quantity'     => $product->pivot->quantity,
                'unit_price'   => $product->pivot->unit_price,
            ];
        });

        return [
            'id'            => $this->id,
            'order_date'    => $this->order_date,
            'total_amount'  => $this->total_amount,
            'status'        => $this->status,
            'customer_id'   => $this->customer_id,
            'customer_name' => $this->customer ? $this->customer->name : null,
            'products'      => $productsSummary,
        ];
    }

    public function getTranslatedStatusAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => ucfirst($this->status),
        };
    }

    public function sendStatusChangeEmail($sendSms = false)
    {
        $customer = $this->customer;
        $products = $this->products;

        $productDetails = '';
        $totalAmount = 0;

        foreach ($products as $product) {
            $subtotal = $product->pivot->quantity * $product->pivot->unit_price;
            $productDetails .= "Producto: {$product->name}\n";
            $productDetails .= "Cantidad: {$product->pivot->quantity}\n";
            $productDetails .= "Precio unitario: {$product->pivot->unit_price} €\n";
            $productDetails .= "Subtotal: {$subtotal} €\n\n";
            $totalAmount += $subtotal;
        }

        $orderDateFormatted = Carbon::parse($this->order_date)->format('d/m/Y');

        $details = [
            'title' => 'Actualización de Estado del Pedido',
            'body' => "Hola {$customer->name},\n\nSu pedido con id #{$this->id} realizado el {$orderDateFormatted} está {$this->getTranslatedStatusAttribute()}.\n\nDetalles del pedido:\n\n$productDetails\nTotal: {$totalAmount} €\n\nGracias por su compra.\n\nSaludos,\nAdvanced Inventory"
        ];

        // Enviar correo electrónico
        Mail::raw($details['body'], function ($message) use ($details, $customer) {
            $message->to($customer->email)
                ->subject($details['title']);
        });

        // Enviar SMS si se solicita
        if ($sendSms) {
            $smsService = new SmsService();
            $phoneNumber = $customer->phone_number;
            $smsMessage = "Hola {$customer->name}, su pedido con id #{$this->id} está {$this->getTranslatedStatusAttribute()}. Total: {$totalAmount} €.";

            $smsService->sendSms($phoneNumber, $smsMessage);
        }
    }
}
