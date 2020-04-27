<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;


class UserCreateAction extends UserAction
{

    protected function action(): Response
    {
        $data = (array)$this->getFormData();

        if (!isset($data["username"]))
            throw new HttpBadRequestException($this->request, "Field Username not found!");

        $id = $this->userRepository->addNewUser(new User(null,
            $data["username"]
        ));

        return $this->respondWithData([
            "userId" => $id
        ]);


    }
}
