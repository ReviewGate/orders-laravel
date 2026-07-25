<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RuntimeException;

/**
 * An order. Money is whole cents (ADR-003).
 *
 * Internal fields (cost_price_cents, manager_note, cancellation_reason) never reach the API
 * response — the contents are set by the Resource (ADR-007).
 */
class Order extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public const CURRENCY_USD = 'USD';

    /** The status is a state machine state: there is no transition from cancelled to paid. */
    private const ALLOWED_TRANSITIONS = [
        self::STATUS_NEW => [self::STATUS_PAID, self::STATUS_CANCELLED],
        self::STATUS_PAID => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
        self::STATUS_SHIPPED => [self::STATUS_DELIVERED],
        self::STATUS_DELIVERED => [],
        self::STATUS_CANCELLED => [],
    ];

    protected $fillable = ['customer_id', 'customer_email', 'total_cents', 'currency'];

    protected $casts = [
        'total_cents' => 'integer',
        'cost_price_cents' => 'integer',
        'created_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** The status changes only through a domain method: it checks whether the transition is allowed (ADR-005). */
    public function changeStatus(string $next): void
    {
        if (! in_array($next, self::ALLOWED_TRANSITIONS[$this->status] ?? [], true)) {
            throw new RuntimeException(
                "Order {$this->id}: the transition from '{$this->status}' to '{$next}' is not allowed"
            );
        }

        $this->status = $next;
        $this->save();
    }

    public function markPaid(string $transactionId): void
    {
        $this->changeStatus(self::STATUS_PAID);
        $this->payment_transaction_id = $transactionId;
        $this->save();
    }
}
