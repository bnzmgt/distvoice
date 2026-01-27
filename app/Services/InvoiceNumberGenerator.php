<?php

namespace App\Services;

use App\Models\InvoiceSequence;
use Illuminate\Support\Facades\DB;

class InvoiceNumberGenerator
{
    public function generate(string $prefix = 'INV'): string
    {
        $year = now()->year;

        return DB::transaction(function () use ($prefix, $year) {

            $sequence = InvoiceSequence::where('prefix', $prefix)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = InvoiceSequence::create([
                    'prefix' => $prefix,
                    'year' => $year,
                    'last_number' => 0,
                ]);
            }

            $nextNumber = $sequence->last_number + 1;

            $sequence->update([
                'last_number' => $nextNumber,
            ]);

            return sprintf(
                '%s/%d/%04d',
                $prefix,
                $year,
                $nextNumber
            );
        });
    }
}
