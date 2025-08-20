<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MMastervalue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MstMastervalueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = MMastervalue::query();

        if ($request->filled('kode')) {
            $query->where('group', 'like', '%' . $request->kode . '%');
        }

        $mastervalues = $query->paginate(10);
        return view('mst_mastervalue.index', compact('mastervalues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil daftar group unik (sebagai collection string)
        $groups = MMastervalue::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group'); // ->pluck supaya $groups = Collection of strings

        return view('mst_mastervalue.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'code'      => ['required', 'string', 'max:36'],
            'name'      => ['required', 'string', 'max:100'],
            'group'     => ['required', 'string'],      // select: bisa '__new__'
            'new_group' => ['nullable', 'string', 'max:100'],
            'isactive'  => ['required', 'in:T,F'],
        ]);

        // Tentukan nama group final
        if ($request->group === '__new__') {
            $newGroup = trim($request->new_group);
            if (empty($newGroup)) {
                return back()->withErrors(['new_group' => 'Masukkan nama grup baru'])->withInput();
            }
            $group = strtoupper($newGroup);
        } else {
            $group = $request->group;
        }

        // Untuk menghindari race condition kecil, bungkus dalam transaction
        $record = DB::transaction(function () use ($request, $group) {
            // Ambil nomor terakhir untuk group ini (pakai lock jika perlu)
            // Note: lockForUpdate() bekerja saat query dalam transaction dan DB engine support
            $lastNumber = MMastervalue::where('group', $group)->lockForUpdate()->max('number');
            $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

            $data = [
                'id'         => Str::uuid()->toString(),
                'code'       => $request->code,
                'name'       => $request->name,
                'group'      => $group,
                'number'     => $nextNumber,
                'isactive'   => $request->isactive,
                'entry_by'   => Auth::check() ? Auth::user()->username : null,
                'entry_date' => now()->toDateString(),
            ];

            return MMastervalue::create($data);
        });

        return redirect()->route('master-value.index')
            ->with('success', 'Master Value berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $value = MMastervalue::findOrFail($id);
        $groups = MMastervalue::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        return view('mst_mastervalue.edit', compact('value', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'code'      => ['required', 'string', 'max:36'],
            'name'      => ['required', 'string', 'max:100'],
            'group'     => ['required', 'string'],
            'new_group' => ['nullable', 'string', 'max:100'],
            'isactive'  => ['required', 'in:T,F'],
        ]);

        // Tentukan group final
        if ($request->group === '__new__') {
            $newGroup = trim($request->new_group);
            if (empty($newGroup)) {
                return back()->withErrors(['new_group' => 'Masukkan nama grup baru'])->withInput();
            }
            $group = strtoupper($newGroup);
        } else {
            $group = $request->group;
        }

        DB::transaction(function () use ($request, $id, $group) {
            $record = MMastervalue::findOrFail($id);

            // Kalau group berubah, update number berdasarkan group baru
            if ($record->group !== $group) {
                $lastNumber = MMastervalue::where('group', $group)->lockForUpdate()->max('number');
                $nextNumber = $lastNumber ? $lastNumber + 1 : 1;
                $record->number = $nextNumber;
                $record->group = $group;
            }

            $record->code = $request->code;
            $record->name = $request->name;
            $record->isactive = $request->isactive;
            $record->update_by = Auth::check() ? Auth::user()->username : null;
            $record->update_date = now()->toDateString();

            $record->save();
        });

        return redirect()->route('master-value.index')
            ->with('success', 'Master Value berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $value = MMastervalue::findOrFail($id);
        $value->delete();

        return redirect()->route('master-value.index')->with('success', 'Data berhasil dihapus');
    }
}
