<?php

namespace App\Http\Controllers\Client\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableBook\CreateTableBookClientRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Table;

class TableController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();
        $tables = Table::all();
        $categories = Category::with('dishes')->get();
        $bookedTableIds = Reservation::pluck('table_id')->toArray(); // Fetch booked table IDs
        return view('clients.table.index', compact('dishes', 'tables', 'bookedTableIds', 'categories'));
    }

    public function store(CreateTableBookClientRequest $request)
    {
        try {
            Reservation::createNewBookTableClient($request->validated());
            flash()->success('Đặt bàn thành công.');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
            return redirect()->back()->withInput();
        }

        return redirect()->route('tableClient');
    }

}
