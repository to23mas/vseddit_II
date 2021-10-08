<?php declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

use App\Controls\Forms\PostForm;
use App\Controls\Forms\SubForumForm;

use Tom\Application\User\Service\UserService;
use Tom\Application\SubForum\Entity\Data\SubForum;
use Tom\Application\SubForum\Service\SubForumService;
final class ForumPresenter extends AbstractAuthorizedPresenter
{

}