<?php

namespace App\Model;

use App\Document\DocumentInterface;
use App\Kernel;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Exception;
use Psr\Log\LoggerInterface;

abstract class AbstractModel
{
    /**
     * @var DocumentManager
     */
    protected DocumentManager $documentManager;
    /**
     * @var string
     */
    protected string $document;

    protected LoggerInterface $logger;

    protected Kernel $rootDir;

    /**
     * @param DocumentManager $documentManager
     * @param LoggerInterface $modelLogger
     * @param Kernel $kernel
     */
    public function __construct(DocumentManager $documentManager, LoggerInterface $modelLogger, Kernel $kernel)
    {
        $this->documentManager = $documentManager;
        $this->logger = $modelLogger;
        $this->rootDir = $kernel;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->getRepo()->findAll();
    }

    /**
     * @return DocumentRepository
     */
    public function getRepo(): DocumentRepository
    {
        return $this->documentManager->getRepository($this->document);
    }

    /**
     * @param array $array
     * @return ?DocumentInterface
     */
    public function findOneBy(array $array): ?DocumentInterface
    {
        return $this->getRepo()->findOneBy($array);
    }

    /**
     * @param DocumentInterface $document
     * @return bool
     */
    public function delete(DocumentInterface $document): bool
    {
        try {
            $this->documentManager->remove($document);
        } catch (Exception $e) {
            $this->logger->error($e);
            return false;
        }
        return true;
    }

    /**
     * @return DocumentInterface
     */
    public function getNewDocument(): DocumentInterface
    {
        return new $this->document;
    }

    /**
     * @param DocumentInterface $document
     * @return bool
     */
    public function save(DocumentInterface $document): bool
    {
        try {
            $this->persist($document);
            $this->flush();
        } catch (Exception $exception) {
            $this->logger->error($exception);
            echo $exception->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @param DocumentInterface $document
     */
    public function persist(DocumentInterface $document)
    {
        $this->documentManager->persist($document);
    }

    /**
     * @return bool|false
     */
    public function flush(): bool
    {
        try {
            $this->documentManager->flush();
        } catch (Exception $exception) {
            $this->logger->error($exception);
            echo $exception->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->documentManager->createQueryBuilder($this->document);
    }

    public function create(DocumentInterface $document, bool $flush = false): bool
    {
        try {
            $this->getDocumentManager()->persist($document);
            if ($flush)
                return $this->flush();
        } catch (Exception $e) {
            $this->logger->warning($e);
            return false;
        }
        return true;
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager(): DocumentManager
    {
        return $this->documentManager;
    }

    public function load(string $id): ?DocumentInterface
    {
        return $this->getDocumentManager()->find($this->document, $id);
    }

    public function update(DocumentInterface $document, bool $flush = false): bool
    {
        try {
            $this->getDocumentManager()->persist($document);
            if ($flush)
                return $this->flush();
        } catch (Exception $e) {
            $this->logger->warning($e);
            return false;
        }
        return true;
    }

    public function getRootDir(): string
    {
        return $this->rootDir->getProjectDir();
    }

    public function getAll()
    {

        return $this->documentManager->getDocumentCollection($this->document)->find();

    }
}
