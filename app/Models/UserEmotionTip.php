<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEmotionTip extends Model
{
    use HasFactory;

    public const EMOTIONS = ['happy', 'sad', 'angry', 'neutral'];

    protected $fillable = [
        'user_id',
        'emotion',
        'tip_order',
        'tip_text',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function defaultTips(): array
    {
        return [
            'happy' => [
                "Pratiquez la gratitude en notant 3 choses pour lesquelles vous êtes reconnaissant ! 🙏✨",
                'Partagez votre bonheur — appelez un ami ou un proche ! 📞❤️',
                'Prenez un moment pour respirer profondément et savourer cette joie ! 🌈😊',
                'Faites quelque chose de gentil pour quelqu\'un d\'autre aujourd\'hui ! 🤗💖',
                'Capturez ce moment — prenez une photo ou écrivez-le dans votre journal ! 📸📝',
            ],
            'sad' => [
                "C'est normal de se sentir triste. Donnez-vous la permission de vous reposer. 😌💙",
                'Écoutez votre playlist musicale réconfortante préférée. 🎵🌧️',
                "Contactez un ami de confiance — vous n'êtes pas seul. 🤝💕",
                'Préparez-vous une boisson chaude et enveloppez-vous dans une couverture douillette. ☕🛋️',
                'Écrivez vos sentiments pour les apprivoiser en douceur. ✍️🌱',
            ],
            'angry' => [
                'Prenez 10 respirations profondes — comptez lentement pour relâcher la tension. 🌬️🔥',
                'Faites une marche rapide pour évacuer cette énergie. 🚶‍♂️💨',
                'Écrivez votre colère sur papier, puis détruisez-la en toute sécurité. 📜🔥',
                'Écoutez des sons apaisants comme la pluie ou les vagues. 🌊🎧',
                'Pratiquez la relaxation musculaire progressive. 💪😤',
            ],
            'neutral' => [
                'Faites une pause pleine conscience de 5 minutes — concentrez-vous sur votre respiration. 🧘‍♀️',
                'Essayez une nouvelle activité créative comme le dessin ou le coloriage. 🎨✏️',
                'Rangez un petit coin de votre espace pour clarifier votre esprit. 🧹✨',
                'Buvez de l\'eau et étirez doucement votre corps. 💧🧘',
                'Lisez une citation inspirante ou une courte histoire positive. 📖🌟',
            ],
        ];
    }
}