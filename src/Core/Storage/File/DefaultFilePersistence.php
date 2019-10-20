<?php

namespace Paru\Core\Storage\File;

use Paru\Core\Storage\Data;
use Paru\Core\Storage\DataPersistence;
use Symfony\Component\Serializer\Serializer;

class DefaultFilePersistence implements DataPersistence {

    /**
     * @var DirectoryHandlerFactory
     */
    private $directoryHandlerFactory;

    /**
     *
     * @var type 
     */
    protected $serializer;

    // TODO: Make configurable
    private const SerializeFormat = 'json';

    public function __construct(DirectoryHandlerFactory $directoryHandlerFactory, Serializer $serializer) {
        $this->directoryHandlerFactory = $directoryHandlerFactory;
        $this->serializer = $serializer;
    }

    public function save(Data $data, bool $overrideExisting = false): void {
        $name = $data->getUniqueName();
        $meta = $data->getMeta();
        $payload = $data->getPayload();

        $fileHelper = $this->directoryHandlerFactory->create($data->getMimeType());
        $saveInOneFile = is_object($payload);

        if ($saveInOneFile) {
            $payload = $this->serializer->serialize($data, self::SerializeFormat);
        } elseif (!empty($meta)) {
            $metaFileName = $this->getMetaFileName($name);
            $serializedMeta = $this->serializer->serialize($meta, self::SerializeFormat);

            $fileHelper->saveFile($metaFileName, $serializedMeta, $overrideExisting);
        }

        $fileHelper->saveFile($name, $payload, $overrideExisting);
    }

    private function getMetaFileName(string $name): string {
        return $name . '.' . self::SerializeFormat;
    }

}
