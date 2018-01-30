<?php declare(strict_types=1);

namespace Symplify\Monorepo\Worker;

use GitWrapper\GitWorkingCopy;

final class RepositoryWorker
{
    public function mergeRepositoryToMonorepoDirectory(string $gitRepository, GitWorkingCopy $gitWorkingCopy): void
    {
        $remoteName = $this->createRepositoryName($gitRepository);

        $this->addRemote($gitRepository, $gitWorkingCopy, $remoteName);

        $gitWorkingCopy->merge($remoteName . '/master', ['allow-unrelated-histories' => true]);
    }

    private function addRemote(string $gitRepository, GitWorkingCopy $gitWorkingCopy, string $remoteName): void
    {
        if ($gitWorkingCopy->hasRemote($remoteName)) {
            return;
        }

        $gitWorkingCopy->addRemote($remoteName, $gitRepository, ['-f' => true]);
    }

    /**
     * This name is needed for git, since it requires [a-zA-Z] string name for remote.
     */
    private function createRepositoryName(string $gitRepository): string
    {
        return md5($gitRepository);
    }
}
