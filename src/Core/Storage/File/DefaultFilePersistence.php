<?php

namespace Paru\Core\Storage\File;

use Exception;
use Paru\Core\Storage\Data;
use Paru\Core\Storage\DataCondition;
use Paru\Core\Storage\DataFinder;
use Paru\Core\Storage\DataPersistence;
use Symfony\Component\Serializer\Serializer;

class DefaultFilePersistence implements DataPersistence, DataFinder {

    /**
     * @var DirectoryHandlerFactory
     */
    private $directoryHandlerFactory;

    /**
     *
     * @var type 
     */
    protected $serializer;

    public function __construct(DirectoryHandlerFactory $directoryHandlerFactory, Serializer $serializer) {
        $this->directoryHandlerFactory = $directoryHandlerFactory;
        $this->serializer = $serializer;
    }

    public function save(Data $data, bool $overrideExisting = false): void {
        $name = $data->getUniqueName();
        $payload = $data->getPayload();

        $fileHandler = $this->directoryHandlerFactory->create('*/*');

        $normalizedData = $this->serializer->normalize($data);
        $saveData = [
            'meta' => [
                'payload_classname' => is_object($payload) ? get_class($payload) : null,
            ],
            'data' => $normalizedData,
        ];

        $fileHandler->saveFile("$name.json", json_encode($saveData), $overrideExisting);
    }

    public function find(string $uniqueName): Data {
        $directoryHandler = $this->directoryHandlerFactory->create('*/*');

        $fileName = "$uniqueName.json";
        if (!$directoryHandler->exists($fileName)) {
            throw new Exception("Data $fileName not found.");
        }

        $content = $directoryHandler->getFileContent($fileName);
        $savedData = json_decode($content, true);
        if (!(array_key_exists('meta', $savedData) && array_key_exists('data', $savedData))) {
            throw new Exception('Malformed data.');
        }

        $meta = $savedData['meta'];
        $payloadData = $savedData['data']['payload'];
        if (array_key_exists('payload_classname', $meta) && $meta['payload_classname'] !== null && !empty($payloadData)) {
            $payloadData = $this->serializer->denormalize($payloadData, $meta['payload_classname']);
        }

        $result = $this->serializer->denormalize($savedData['data'], Data::class);

        return new Data($result->getUniqueName(), $payloadData, $result->getMimeType(), $result->getMeta());
    }

    public function list(string $mimeType, DataCondition $condition): array {
        throw new Exception('Not implemented yet.');
    }

}
