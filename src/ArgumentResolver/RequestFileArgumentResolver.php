<?php

namespace App\ArgumentResolver;

use App\Attribute\RequestFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestFileArgumentResolver implements ValueResolverInterface
{
    public function __construct(private ValidatorInterface $validator)
    {

    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var RequestFile $attribute */
        $attribute = $argument->getAttributes(RequestFile::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? new RequestFile('');
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get($attribute->getField());

        $errors = $this->validator->validate($uploadedFile, $attribute->getConstraints());

        if (count($errors) > 0) {
            throw new ValidationFailedException($attribute->getField(), $errors);
        }

        yield $uploadedFile;
    }
}
