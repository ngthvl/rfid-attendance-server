<?php
namespace Tamani\RfidTerminal\Models;

use App\Support\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidTagAllocation extends Model
{
    use HasFactory, UUIDModel;

    const FILLABLE = [
        'tag_data',
        'allocation_id',
        'allocation_type',
    ];

    /**
     * RfidTagAllocation constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }


    public function allocation(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('allocation');
    }
}
