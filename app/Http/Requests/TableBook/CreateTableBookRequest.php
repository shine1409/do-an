<?php

namespace App\Http\Requests\TableBook;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Reservation;

class CreateTableBookRequest extends FormRequest
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
            ],
            'product_id' => 'nullable',
            'user_id' => 'required',
            'quantities.*' => [
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $productId = explode('.', $attribute)[1];
                    $product = Product::find($productId);

                    if ($product && $value > $product->quantity) {
                        $fail("Số lượng món \"{$prduct->name}\" đã vượt quá số lượng có sẵn.");
                    }
                }
            ],
            'note' => 'nullable|string',
            'seats' => 'nullable|required',
            'status' => 'required|string',
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
            'name.string' => 'Tên người đặt phải là một chuỗi ký tự.',
            'name.max' => 'Tên người đặt không được vượt quá 255 ký tự.',
            'table_id.required' => 'Chọn bàn là bắt buộc.',
            'table_id.unique' => 'Bàn đã được đặt.',
            'table_id.exists' => 'Bàn không tồn tại.',
            'product_id.required' => 'Món ăn là bắt buộc.',
            'product_id.array' => 'Món ăn phải là một mảng.',
            'product_id.*.exists' => 'Món ăn không tồn tại.',
            'quantities.required' => 'Số lượng món ăn là bắt buộc.',
            'quantities.array' => 'Số lượng món ăn phải là một mảng.',
            'quantities.*.integer' => 'Số lượng món ăn phải là số nguyên.',
            'quantities.*.min' => 'Số lượng món ăn phải ít nhất là 1.',
            'note.string' => 'Ghi chú phải là một chuỗi ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'reservation_date.required' => 'Ngày đặt không được để trống.',
            'reservation_date.date' => 'Ngày đặt không hợp lệ.',
            'reservation_date.after_or_equal' => 'Ngày đặt phải là hôm nay hoặc trong tương lai.',
            'reservation_time.required' => 'Giờ đặt không được để trống.',
            'reservation_time.date_format' => 'Giờ đặt phải theo định dạng HH:mm.',
            'seats.required' => 'Ghế ngồi không được để trống',
        ];
    }
}
