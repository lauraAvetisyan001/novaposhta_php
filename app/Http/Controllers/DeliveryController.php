<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function sendDeliveryData(Request $request)
    {
        // Получение данных о посылке и получателе из запроса
        $shipmentData = $request->only(['width', 'height', 'length', 'weight']);
        $recipientData = $request->only(['customer_name', 'phone_number', 'email', 'address']);

        // Формирование данных для отправки на сервер Новой почты
        $postData = [
            'customer_name' => $recipientData['customer_name'],
            'phone_number' => $recipientData['phone_number'],
            'email' => $recipientData['email'],
            'sender_address' => config('app.sender_address'),
            'delivery_address' => $recipientData['address']
        ];

        // Отправка данных на сервер Новой почты
        $response = Http::post('https://novaposhta.test/api/delivery', $postData);

        // Проверка статуса ответа
        if ($response->successful()) {
            // Действия в случае успешной отправки данных
            return response()->json(['message' => 'Данные успешно отправлены на сервер Новой почты']);
        } else {
            // Действия в случае ошибки при отправке данных
            return response()->json(['error' => 'Ошибка при отправке данных на сервер Новой почты'], 500);
        }
    }
}

