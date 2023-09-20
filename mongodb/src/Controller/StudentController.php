<?php
namespace App\Controller;

use App\Document\Student;
use App\Service\StudentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StudentController extends AbstractController {

    /**
     * @Route("/students", name="student_index", methods={"GET"})
     */
    public function index(StudentService $studentService, SerializerInterface $serializer): JsonResponse
    {
        try {
            $students = $studentService->getAllStudents();
            $jsonData = $serializer->serialize($students, 'json');

            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            if ($e->getMessage() === "No students found") {
                return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_show", methods={"GET"})
     */
    public function show($id, StudentService $studentService, SerializerInterface $serializer): JsonResponse
    {
        try {
            $student = $studentService->getStudentById($id);
            $jsonData = $serializer->serialize($student, 'json');
            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            if ($e->getMessage() === "Student not found with id $id") {
                return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students", name="student_create", methods={"POST"})
     */
    public function createStudent(
        SerializerInterface $serializer,
        StudentService $studentService,
        Request $request
    ): JsonResponse {
        try {
            $jsonContent = $request->getContent();
            $student = $serializer->deserialize($jsonContent, Student::class, 'json');

            if (!$student) {
                return new JsonResponse('Invalid request data', Response::HTTP_BAD_REQUEST);
            }

            $studentService->createStudent($student);

            return new JsonResponse('Student created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_delete", methods={"DELETE"})
     */
    public function deleteStudent($id, StudentService $studentService): JsonResponse
    {
        try {
            $studentService->deleteStudent($id);
            return new JsonResponse('Student deleted', Response::HTTP_OK);
        } catch (Exception $e) {
            if ($e->getMessage() === "Student not found with id $id") {
                return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_update", methods={"PUT"})
     */
    public function updateStudent(
        $id,
        SerializerInterface $serializer,
        Request $request,
        StudentService $studentService
    ): JsonResponse {
        try {
            $jsonContent = $request->getContent();
            $updatedStudent = $serializer->deserialize($jsonContent, Student::class, 'json');

            if (!$updatedStudent) {
                return new JsonResponse('Invalid request data', Response::HTTP_BAD_REQUEST);
            }

            $student = $studentService->updateStudent($id, $updatedStudent);

            return new JsonResponse($student, Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            if ($e->getMessage() === "Student not found with id $id") {
                return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}