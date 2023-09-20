<?php
namespace App\Service;

use App\Document\Student;
use App\Exception\StudentException;
use App\Repository\StudentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;


class StudentService {
    private $documentManager;
    private $studentRepository;

    public function __construct(DocumentManager $documentManager, StudentRepository $studentRepository){
        $this->documentManager = $documentManager;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Get all students.
     *
     * @return Student[]
     * @throws StudentException
     */
    public function getAllStudents(): array
    {
        $students = $this->studentRepository->findAll();
        if (count($students) === 0) {
            throw new StudentException("No students found");
        }
        return $students;
    }

    /**
     * Get a student by ID.
     *
     * @param string $id
     * @return Student
     * @throws StudentException
     */
    public function getStudentById(string $id): Student
    {
        $student = $this->studentRepository->find($id);
        if (!$student) {
            throw new StudentException("Student not found with id $id");
        }
        return $student;
    }

    /**
     * Create a new student.
     *
     * @param Student $student
     */
    public function createStudent(Student $student): void
    {
        $this->documentManager->persist($student);
        $this->documentManager->flush();
    }

    /**
     * Update a student by ID.
     *
     * @param string $id
     * @param Student $updatedStudent
     * @return Student
     * @throws StudentException
     */
    public function updateStudent(string $id, Student $updatedStudent): Student
    {
        $existingStudent = $this->studentRepository->find($id);
        if (!$existingStudent) {
            throw new StudentException("Student not found with id $id");
        }
        $existingStudent->setName($updatedStudent->getName());
        $existingStudent->setAge($updatedStudent->getAge());
        $existingStudent->setBranch($updatedStudent->getBranch());

        try {
            $this->documentManager->flush();
        } catch (Exception $e) {
            throw new StudentException("Error updating student: " . $e->getMessage());
        }

        return $existingStudent;
    }

    /**
     * Delete a student by ID.
     *
     * @param string $id
     * @throws StudentException
     */
    public function deleteStudent(string $id): void
    {
        $student = $this->studentRepository->find($id);
        if (!$student) {
            throw new StudentException("Student not found with id $id");
        }
        $this->documentManager->remove($student);
        try {
            $this->documentManager->flush();
        } catch (Exception $e) {
            throw new StudentException("Error deleting student: " . $e->getMessage());
        }
    }
}