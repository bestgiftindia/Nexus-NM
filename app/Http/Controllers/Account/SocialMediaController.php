<?php

namespace App\Http\Controllers\Account;

use App\Enums\Message;
use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use App\Services\flashService;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public $flasherService;

    function __construct(flashService $flasher)
    {
        $this->flasherService = $flasher;
    }

    function index()
    {
        $lists = SocialMedia::get();
        return view('account.social-media', compact('lists'));
    }

    function update(Request $request)
    {
        $request->validate([
            'social_media' => ['required', 'array'],
            'social_media.*' => [
                'nullable',
                function ($attribute, $value, $fail) {

                    // social_media.5 se ID nikalega
                    $id = explode('.', $attribute)[1];

                    // WhatsApp ID = 5
                    if ($id == 6) {
                        if (!preg_match('/^\+?[0-9]{10,15}$/', $value)) {
                            $fail('Please enter a valid WhatsApp number.');
                        }

                        return;
                    }

                    // Other social media fields
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Please enter a valid URL.');
                    }
                },
            ],
        ]);

        foreach ($request->social_media as $id => $link) {
            SocialMedia::where('id', $id)->update([
                'link' => $link,
            ]);
        }
        $this->flasherService->successService(Message::SOCIALMEDIAUPDATE->value);
        return redirect()
            ->back();
    }
}
