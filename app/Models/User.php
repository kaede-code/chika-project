<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

    #[Fillable(['name', 'no_hp', 'alamat', 'password', 'role'])]


#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFormattedAlamatAttribute(): string
    {
        $alamat = $this->alamat ?? '';
        if ($alamat === '' || $alamat === '-') return '-';
        if (!str_contains($alamat, '||')) return $alamat;
        $parts = explode('||', $alamat);
        $result = [];
        if (trim($parts[0] ?? '')) $result[] = trim($parts[0]);
        if (trim($parts[1] ?? '')) $result[] = 'Kec. ' . trim($parts[1]);
        if (trim($parts[2] ?? '')) $result[] = 'Kab./Kota. ' . trim($parts[2]);
        if (trim($parts[3] ?? '')) $result[] = 'Prov. ' . trim($parts[3]);
        return implode(', ', $result) ?: '-';
    }

    /**
     * Laravel default assumes email exists. For this project, authentication is via no_hp,
     * so override email accessors used by some auth flows.
     */
    public function getEmailForPasswordReset(): mixed
    {
        return null;
    }
}
