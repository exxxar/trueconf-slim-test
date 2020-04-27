<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

 class UserRemoveAction extends UserAction
{

    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfIdAndRemove($userId);

        $this->logger->info("User of id `${userId}` was removed.");

        return $this->respondWithData($user);

    }
}
