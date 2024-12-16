<?php

namespace App\Http\Controllers\Admin\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableBook\CreateTableBookRequest;
use App\Http\Requests\TableBook\UpdateTableBookRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservation = Reservation::orderBy('created_at', 'desc')->paginate(5);
        return view('admin.booktable.list', compact('reservation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add()
    {
        $users = User::all();
        $tables = Table::all();
        $productes = Product::all();
        $categories = Category::with('productes')->get();
        return view('admin.booktable.add', compact('users', 'tables', 'productes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTableBookRequest $request)
    {
        try {
            $validated = $request->validated();
            $reservation = Reservation::createNewBookTable($validated);

            $table = Table::findOrFail($validated['table_id']);
            // $table->update(['seats' => $validated['seats']]);

            flash()->success('Thêm thành công.');
        } catch (\Exception $e) {
            flash()->error('Error: ' . $e->getMessage());
        }

        return redirect()->route('table-book.list');
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $users = User::all();
        $tables = Table::all();
        $productes = Product::all();
        $categories = Category::with('productes')->get();
        return view('admin.booktable.edit', compact('reservation', 'users', 'tables', 'productes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableBookRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->updateNewBookTable($request->validated());

        $table = Table::findOrFail($request->validated('table_id'));

        flash()->success('Cập nhật thành công.');
        return redirect()->route('table-book.list');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        flash()->success('Xóa thành công.');
        return redirect()->route('table-book.list');
    }

    /**
     * Fetch table details.
     */
    // In TableBookController
    public function getTableDetails($id)
    {
        $table = Table::findOrFail($id);
        return response()->json($table);
    }


    public function checkTableAvailability(Request $request)
    {
        $date = $request->input('reservation_date');
        $time = $request->input('reservation_time');
        $endTime = date('H:i:s', strtotime($time . ' +3 hours'));

        $unavailableTables = Reservation::where('reservation_date', $date)
            ->where(function ($query) use ($time, $endTime) {
                $query->whereBetween('reservation_time', [$time, $endTime])
                    ->orWhereBetween(DB::raw("DATE_ADD(reservation_time, INTERVAL 3 HOUR)"), [$time, $endTime]);
            })
            ->pluck('table_id')
            ->toArray();

        $allTables = Table::pluck('id')->toArray();

        return response()->json([
            'unavailableTables' => $unavailableTables,
            'allTables' => $allTables
        ]);
    }


    public function filterProductes(Request $request)
    {
        $query = Product::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $productes = $query->get();

        return response()->json($productes);
    }
}
