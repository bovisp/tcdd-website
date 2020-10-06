<?php

namespace App;

use App\Section;
use App\Assessment;
use App\Traits\HasSupervisors;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,
        HasRoles,
        HasSupervisors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'moodle_id',
        'section_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'firstname',
        'lastname',
        'fullname',
        'role'
    ];

    public function moodleuser()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'moodle_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }

    public function assessmentEditor()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_editors', 'editor_id', 'assessment_id');
    }

    public function assessmentParticipant()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_participants', 'participant_id', 'assessment_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'author_id');
    }

    public function questionEditor()
    {
        return $this->belongsToMany(Question::class, 'question_editors', 'user_id', 'question_id');
    }

    public function getFirstnameAttribute()
    {
        return $this->moodleProfile('firstname');
    }

    public function getLastnameAttribute()
    {
        return $this->moodleProfile('lastname');
    }

    public function getFullnameAttribute()
    {
        return $this->moodleProfile('firstname') . ' ' . $this->moodleProfile('lastname');
    }

    public function getRoleAttribute()
    {
        return optional($this->roles
            ->where('name', '!=', 'administrator')
            ->first())
            ->name;
    }

    protected function moodleProfile($column)
    {
        $user = User::find($this->id);

        return MoodleUser::whereId($user->moodle_id)
            ->first()
            ->{$column};
    }
}
