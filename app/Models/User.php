<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;

    class User extends Authenticatable
    {
        use HasFactory, Notifiable;

        protected $fillable = [
            'name',
            'email',
            'password',
            'role',
            'nim',
            'nip',
            'phone',
            'address',
        ];

        protected $hidden = [
            'password',
            'remember_token',
        ];

        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ];
        }

        public function isAdmin(): bool
        {
            return $this->role === 'admin';
        }

        public function isMahasiswa(): bool
        {
            return $this->role === 'mahasiswa';
        }

        public function isPegawai(): bool
        {
            return $this->role === 'pegawai';
        }

        public function notifications()
        {
            return $this->hasMany(\App\Models\Notification::class, 'user_id');
        }
        

        public function unreadNotifications()
        {
            return $this->notifications()->where('is_read', false);
        }

        
        public function loans()
        {
            return $this->hasMany(Loan::class);
        }

        public function reviews()
        {
            return $this->hasMany(Review::class);
        }
    }