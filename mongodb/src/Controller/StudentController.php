<?php
namespace App\Controller;

use App\Document\Student;
use App\Model\StudentModel;
use App\Service\StudentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StudentController extends AbstractController {

    protected StudentModel $studentModel;
    protected SerializerInterface $serializer;

    public function __construct(
        StudentModel $studentModel, 
        SerializerInterface $serializer)
    {
        $this->studentModel = $studentModel;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/students", name="student_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        try {
            $students = $this->studentModel->findAll();
            if(count($students) == 0){
                return new JsonResponse("Students Not Found", Response::HTTP_NOT_FOUND);
            }
            $jsonData = $this->serializer->serialize($students, 'json');

            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_show", methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        try {
            $student = $this->studentModel->load($id);
            if (!$student) {
                return new JsonResponse("Student not found with id $id", Response::HTTP_NOT_FOUND);
            }
            $jsonData = $this->serializer->serialize($student, 'json');
            return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students", name="student_create", methods={"POST"})
     */
    public function createStudent(
        Request $request
    ): JsonResponse {
        try {
            $jsonContent = $request->getContent();
            $student = $this->serializer->deserialize($jsonContent, Student::class, 'json');

            if (!$student) {
                return new JsonResponse('Invalid request data', Response::HTTP_BAD_REQUEST);
            }
            $this->studentModel->create($student);
            $this->studentModel->flush();
            return new JsonResponse('Student created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_delete", methods={"DELETE"})
     */
    public function deleteStudent($id): JsonResponse
    {
        try {
            $student = $this->studentModel->load($id);
            if (!$student) {
                return new JsonResponse("Student not found with id $id", Response::HTTP_BAD_REQUEST);
            }
            $this->studentModel->delete($student);
            $this->studentModel->flush();
            return new JsonResponse('Student deleted', Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/students/{id}", name="student_update", methods={"PUT"})
     */
    public function updateStudent(
        $id,
        Request $request
    ): JsonResponse {
        try {
            $jsonContent = $request->getContent();
            $updatedStudent = $this->serializer->deserialize($jsonContent, Student::class, 'json');

            if (!$updatedStudent) {
                return new JsonResponse('Invalid request data', Response::HTTP_BAD_REQUEST);
            }
            $student = $this->studentModel->load($id);
            if (!$student) {
                return new JsonResponse("Student not found with id $id", Response::HTTP_BAD_REQUEST);
            }
            $this->studentModel->save($updatedStudent);
            $this->studentModel->flush();

            return new JsonResponse($updatedStudent, Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return new JsonResponse('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}