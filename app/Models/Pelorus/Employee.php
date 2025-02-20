<?php

namespace App\Models\Pelorus;

class Employee extends BaseModel
{
    protected $connection = 'pelorus';
    protected $table = '999.Employees';

    protected $fillable = [
        'FirstName',
        'MiddleName',
        'LastName',
        'Department',
        'Position',
        'Type',
        'HireDate',
        'RehireDate',
        'TerminationDate',
        'Status'
    ];

    protected $dates = [
        'HireDate',
        'RehireDate',
        'TerminationDate'
    ];
}
