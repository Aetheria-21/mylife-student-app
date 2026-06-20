<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can save a gender theme preference', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('set.gender'), [
        'gender' => 'female',
        'emotion_tips' => customEmotionTips(),
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'gender' => 'female',
        ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'gender' => 'female',
        'has_visited_welcome' => 1,
    ]);

    $this->assertDatabaseHas('user_emotion_tips', [
        'user_id' => $user->id,
        'emotion' => 'happy',
        'tip_order' => 1,
        'tip_text' => 'Celebrate with a short gratitude note.',
    ]);
});

test('home page exposes the saved gender theme', function () {
    $user = User::factory()->create([
        'gender' => 'female',
    ]);

    $user->emotionTips()->createMany([
        ['emotion' => 'happy', 'tip_order' => 1, 'tip_text' => 'Celebrate with a short gratitude note.'],
        ['emotion' => 'happy', 'tip_order' => 2, 'tip_text' => 'Call someone you love and share the joy.'],
        ['emotion' => 'happy', 'tip_order' => 3, 'tip_text' => 'Dance for two minutes to keep the energy.'],
        ['emotion' => 'happy', 'tip_order' => 4, 'tip_text' => 'Take a happy selfie for your memories.'],
        ['emotion' => 'happy', 'tip_order' => 5, 'tip_text' => 'Write one win from today in your journal.'],
    ]);

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    $response->assertSee('data-user-gender="female"', false);
    $response->assertSee('theme-female', false);
    $response->assertSee('Celebrate with a short gratitude note.', false);
});

test('authenticated user can update settings from home', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'gender' => 'male',
    ]);

    $response = $this->actingAs($user)->patch(route('settings.profile.update'), [
        'name' => 'New Name',
        'gender' => 'female',
        'emotion_tips' => customEmotionTips(),
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('settings_status', 'Settings updated successfully.');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'gender' => 'female',
    ]);

    $this->assertDatabaseHas('user_emotion_tips', [
        'user_id' => $user->id,
        'emotion' => 'neutral',
        'tip_order' => 5,
        'tip_text' => 'Read one uplifting quote.',
    ]);
});

function customEmotionTips(): array
{
    return [
        'happy' => [
            'Celebrate with a short gratitude note.',
            'Call someone you love and share the joy.',
            'Dance for two minutes to keep the energy.',
            'Take a happy selfie for your memories.',
            'Write one win from today in your journal.',
        ],
        'sad' => [
            'Pause and breathe for one calm minute.',
            'Wrap yourself in a blanket and rest.',
            'Message one safe person you trust.',
            'Drink warm tea slowly and gently.',
            'Write what hurts without judging yourself.',
        ],
        'angry' => [
            'Step away for five deep breaths.',
            'Go walk fast for ten minutes.',
            'Write the anger down before reacting.',
            'Listen to calming rain sounds.',
            'Stretch your shoulders and jaw slowly.',
        ],
        'neutral' => [
            'Take a mindful pause and scan your body.',
            'Drink a glass of water.',
            'Tidy one small area around you.',
            'Do a quick creative sketch.',
            'Read one uplifting quote.',
        ],
    ];
}