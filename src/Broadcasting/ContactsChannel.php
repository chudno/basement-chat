<?php

declare(strict_types=1);

namespace BasementChat\Basement\Broadcasting;

use BasementChat\Basement\Data\ContactData;
use BasementChat\Basement\Facades\Basement;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ContactsChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \Illuminate\Foundation\Auth\User&\BasementChat\Basement\Contracts\User $user
     *
     * @return array<string,mixed>
     */
    public function join(Authenticatable $user): array
    {
        $user = $user->chatUser();

        /** @var \Illuminate\Foundation\Auth\User&\BasementChat\Basement\Contracts\User $contact */
        $contact = Basement::newUserModel()->findOrFail($user->id);
        $contact->append('avatar');

        return (new ContactData(
            id: (int) $contact->id,
            name: $contact->name,
            avatar: $contact->avatar,
            last_private_message: null,
        ))->toArray();
    }
}
