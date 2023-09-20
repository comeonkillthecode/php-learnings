<?php
namespace App\Model;
use App\Model\AbstractModel;
use App\Document\Student;

class StudentModel extends AbstractModel
{
    protected string $document = Student::class;
}