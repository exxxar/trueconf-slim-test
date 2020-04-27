<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use InvalidArgumentException;


class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null)
    {
        $uploaded_users = json_decode(file_get_contents('..\public\users.json'));

        $this->users = [];

        if (count($uploaded_users) == 0)
            $this->users = $users ?? [
                    new User(1, 'bill.gates'),
                    new User(2, 'steve.jobs'),
                    new User(3, 'mark.zuckerberg'),
                    new User(4, 'evan.spiegel'),
                    new User(5, 'jack.dorsey'),
                ];
        else
            foreach ($uploaded_users as $key => $user) {
                echo ($user)->username;
                array_push($this->users, new User($key + 1, $user->username));
            }

    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        $tmp_users = array_filter($this->users, function ($item) use ($id) {
            return $item->getId() === $id;
        });

        if (count($tmp_users) === 0 || is_null($tmp_users)) {
            throw new UserNotFoundException();
        }

        return array_pop($tmp_users);
    }

    /**
     * @inheritDoc
     */
    public function findUserOfIdAndRemove(int $id): User
    {
        $removed_user = array_filter($this->users, function ($item) use ($id) {
            return $item->getId() === $id;
        });

        $tmp_users = array_filter($this->users, function ($item) use ($id) {
            return $item->getId() !== $id;
        });

        $this->users = $tmp_users;
        $this->store();

        return array_pop($removed_user);
    }

    /**
     * @inheritDoc
     */
    public function addNewUser(User $user): int
    {
        array_push($this->users, new User($user->getId() ?? $this->getMaxId() + 1, $user->getUsername()));
        $this->store();
        return $this->getMaxId();
    }

    /**
     * @inheritDoc
     */
    public function updateUserById(int $id, User $user): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }
        $index = $this->getUserIndex($id);

        if (!$index)
            throw new InvalidArgumentException("Invalid Id!");

        $this->users[$index] = new User($this->users[$index]->getId(), $user->getUsername());
        $this->store();

        return $this->users[$index];
    }

    public function store()
    {
        file_put_contents('../public/users.json', json_encode(array_values($this->users)));
    }

    protected function getMaxId()
    {
        $max = $this->users[0]->getId() ?? -1;

        foreach ($this->users as $user)

            $max = max($user->getId(), $max);

        return $max;
    }

    protected function getUserIndex(int $id)
    {
        $index = false;

        foreach ($this->users as $key => $user)
            if ($user->getId() === $id)
                $index = $key;

        return $index;
    }
}
