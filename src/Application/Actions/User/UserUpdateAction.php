<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

class UserUpdateAction extends UserAction
{

    protected function action(): Response
    {
        $data = (array)$this->getFormData();

        if (!isset($data["username"]))
            throw new HttpBadRequestException($this->request, "Field Username not found!");

        $id = intval($this->resolveArg('id'));

        $id = $this->userRepository->updateUserById($id,new User(null,
            $data["username"]
        ));

        return $this->respondWithData([
            "userId" => $id
        ]);

        return $this->respondWithData([
            "test" => "tetttt"
        ]);
    }
}
