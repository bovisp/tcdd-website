<?php

namespace App\Traits;

use App\Supervisor;

trait HasSupervisors
{
    protected $employeeArr = [];

    protected $supervisorArr = [];
    
    public function supervisors()
    {
        return $this->belongsToMany(Supervisor::class, 'supervisors_users');
    }

    public function reportingStructure()
    {
        if ($this->hasAnyReportingStructure() === true) {
            return collect();
        }

        if (optional($this->supervisors)->count() === 0) {
            $this->getEmployees();

            return collect($this->employeeArr)
                ->unique()
                ->groupBy('role');
        }

        if (optional($this->supervisor)->users === null) {
            $this->getSupervisors();

            return collect($this->supervisorArr)
                ->unique()
                ->groupBy('role');
        }

        $this->getEmployees();

        $this->getSupervisors();

        return collect($this->supervisorArr)
            ->unique()
            ->merge(collect($this->employeeArr)->unique())
            ->groupBy('role');
    }

    protected function getEmployees()
    {
        $employees = $this->supervisor->users->load(
            'moodleuser', 'roles'
        );

        $this->mappedEmployees($employees);
    }

    protected function getSupervisors()
    {
        $supervisors = $this->supervisors->each->load(
            'user.moodleuser', 'user.roles'
        );

        $this->mappedSupervisors($supervisors);
    }

    protected function hasAnyReportingStructure()
    {
        return optional($this->supervisors)->count() === 0 &&
               optional($this->supervisor)->users === null;
    }

    protected function mappedEmployees($employees)
    {
        foreach ($employees as $employee) {
            $this->employeeArr[] = $this->getEmployeeMeta($employee);

            if (optional($employee->supervisor)->users !== null) {
                $this->mappedEmployees(
                    $employee->supervisor->users->load(
                        'moodleuser', 'roles'
                    )
                );
            }
        }
    }

    protected function mappedSupervisors($supervisors)
    {
        foreach ($supervisors as $supervisor) {
            $this->supervisorArr[] = $this->getSupervisorMeta($supervisor);

            if (optional($supervisor->user)->supervisors !== null) {
                $this->mappedSupervisors(
                    $supervisor->user->supervisors->each->load(
                        'user.moodleuser', 'user.roles'
                    )
                );
            }
        }
    }

    protected function getEmployeeMeta($employee)
    {
        return [
            'id' => $employee->id, 
            'role' => $employee->roles->where('name', '!=', 'administrator')->first()->name, 
            'rank' => $employee->roles->where('name', '!=', 'administrator')->first()->rank,
            'firstname' => $employee->moodleuser->firstname, 
            'lastname' => $employee->moodleuser->lastname
        ];
    }

    protected function getSupervisorMeta($supervisor)
    {
        return [
            'id' => $supervisor->user_id, 
            'role' => $supervisor->user->roles->where('name', '!=', 'administrator')->first()->name, 
            'rank' => $supervisor->user->roles->where('name', '!=', 'administrator')->first()->rank, 
            'firstname' => $supervisor->user->moodleuser->firstname, 
            'lastname' => $supervisor->user->moodleuser->lastname
        ];
    }
}