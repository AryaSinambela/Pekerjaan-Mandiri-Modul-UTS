<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanRequest extends Model
{
    protected $table = 'loan_requests';

    protected $fillable = [
        'equipment_id',
        'borrower_name',
        'borrower_nim',
        'borrower_email',
        'loan_date',
        'expected_return_date',
        'purpose',
        'status',
        'total_estimated_fee',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'expected_return_date' => 'date',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }
}