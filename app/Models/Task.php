<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'study' => 'Études',
        'work' => 'Travail',
        'personal' => 'Personnel',
        'health' => 'Santé',
        'finance' => 'Finances',
        'family' => 'Famille',
        'chores' => 'Ménage',
        'spiritual' => 'Spirituel',
        'hobby' => 'Loisirs',
        'errands' => 'Courses',
        'other' => 'Autre',
    ];

    public const PRIORITIES = [
        'low' => 'Basse',
        'medium' => 'Moyenne',
        'high' => 'Haute',
    ];

    public const PRIORITY_COLORS = [
        'low'    => 'bg-green-100 text-green-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'high'   => 'bg-red-100 text-red-700',
    ];

    public const PRIORITY_ICONS = [
        'low'    => '✨',
        'medium' => '⚡',
        'high'   => '🔥',
    ];

    public const CATEGORY_ICONS = [
        'study' => '📚',
        'work' => '💼',
        'personal' => '🌟',
        'health' => '💪',
        'finance' => '💰',
        'family' => '👨‍👩‍👧',
        'chores' => '🧹',
        'spiritual' => '🕌',
        'hobby' => '🎨',
        'errands' => '🛍️',
        'other' => '🗂️',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'due_date',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function iconFor(string $category): string
    {
        return self::CATEGORY_ICONS[$category] ?? '🗂️';
    }
}