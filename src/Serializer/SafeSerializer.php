<?php


namespace App\Serializer;


use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SafeSerializer
{
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface  $serializer, ValidatorInterface  $validator)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function safeDeserialize($data, $type, $format = 'json', $context = [], $existingData = '')
    {
        switch ($format) {
            case 'json':
            default:
                $object = $this->serializer->deserialize($data, $type, $format, $context);
                break;
            case 'object':
                $arrayDto = $this->serializer->normalize($data);
                $object = $this->serializer->denormalize(
                    $arrayDto,
                    $type,
                    null,
                    array_merge($context, [AbstractNormalizer::OBJECT_TO_POPULATE => $existingData])
                );
                break;
        }
        $violations = $this->validator->validate($object);

        if (0 !== count($violations)) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $payload = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];

            throw new SafeSerializerValidationException($payload);
        }

        return $object;
    }
}