<?php
namespace App\Repository;

use App\Document\Student;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class StudentRepository extends DocumentRepository
{
    public function __construct(DocumentManager $documentManager){
        $unitOfWork = $documentManager->getUnitOfWork();
        $classMetaData = $documentManager->getClassMetadata(Student::class);
        parent::__construct($documentManager,$unitOfWork, $classMetaData);
    }
}
