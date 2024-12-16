<?php

namespace App\Http\Requests\TableBook;

use App\Models\Product;
use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTableBookClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'table_id' => [
                'required',
                'exists:tables,id',
                function ($attribute, $value, $fail) {
                    if (Reservation::where('table_id', $value)
                        ->where('reservation_date', $this->reservation_date)
                        ->where('reservation_time', '>', Carbon::now()->addHours(2)->format('H:i'))
                        ->exists()
                    ) {
                        $fail('Bàn này đã được đặt vào ngày hôm nay và vui lòng đặt trong vòng 2 giờ tới.');
                        // Lấy ngày và giờ đặt bàn từ yêu cầu
                        $reservationDate = Carbon::parse($this->reservation_date);
                        $reservationTime = Carbon::parse($this->reservation_time);

                        // Tìm kiếm các đơn hàng đã tồn tại với bàn và ngày tương ứng
                        $existingOrders = Reservation::where('table_id', $value)
                            ->where('reservation_date', $reservationDate->toDateString())
                            ->get();

                        foreach ($existingOrders as $order) {
                            $existingOrderTime = Carbon::parse($order->reservation_time);

                            // So sánh thời gian đặt trong vòng 3 giờ
                            if ($reservationTime->between($existingOrderTime->copy()->subHours(3), $existingOrderTime->copy()->addHours(3))) {
                                $fail('Bàn này đã được đặt và các đơn hàng phải cách nhau ít nhất 3 giờ.');
                                break;
                            }
                        }
                    }
                }
            ],

            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:productes,id',
            'quantities.*' => [
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $productId = explode('.', $attribute)[1];
                    $product = Product::find($productId);

                    if ($product && $value > $prodcut->quantity) {
                        $fail("Số lượng món \"{$product->name}\" đã vượt quá số lượng có sẵn.");
                    }
                }
            ],

            'note' => 'nullable|string',
            'phone' => 'required|digits_between:10,15',
            'reservation_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isPast()) {
                        $fail('Ngày đặt bàn phải là hôm nay hoặc trong tương lai.');
                    }
                },
            ],
            'reservation_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $orderDate = Carbon::parse($this->reservation_date);
                    $orderTime = Carbon::createFromFormat('H:i', $value);
                    if ($orderDate->isToday() && $orderTime->lessThan(Carbon::now()->addHours(2))) {
                        $fail('Giờ đặt phải trước ít nhất 2 giờ so với thời gian hiện tại.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Người đặt bàn là bắt buộc.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.digits_between' => 'Số điện thoại phải là số và có độ dài từ 10 đến 15 chữ số.',
            'name.string' => 'Tên người đặt phải là một chuỗi ký tự.',
            'name.max' => 'Tên người đặt không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên người đặt đã tồn tại.',
            'table_id.required' => 'Chọn bàn là bắt buộc.',
            'table_id.unique' => 'Bàn đã được đặt.',
            'table_id.exists' => 'Bàn không tồn tại.',
            'note.string' => 'Ghi chú phải là một chuỗi ký tự.',
            'reservation_date.required' => 'Ngày đặt không được để trống.',
            'reservation_date.date' => 'Ngày đặt không hợp lệ.',
            'reservation_date.after_or_equal' => 'Ngày đặt phải là hôm nay hoặc trong tương lai.',
            'reservation_time.required' => 'Giờ đặt không được để trống.',
            'reservation_time.date_format' => 'Giờ đặt phải theo định dạng HH:mm.',
        ];
    }
}
