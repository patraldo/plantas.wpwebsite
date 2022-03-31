<?php

declare(strict_types=1);

namespace Inpsyde\Zettle\ProductSettings\Metabox;

use Inpsyde\Zettle\PhpSdk\Repository\Zettle\Product\ProductRepositoryInterface;
use MetaboxOrchestra\BoxAction;
use MetaboxOrchestra\BoxInfo;
use MetaboxOrchestra\BoxView;
use MetaboxOrchestra\Entity;
use MetaboxOrchestra\PostMetabox;
use WP_Post;

// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar

class ZettleProductLibraryLink implements PostMetabox
{

    public const ID = 'zettle-product-library-link';

    /**
     * @var ProductRepositoryInterface
     */
    private $repository;

    /**
     * @var BoxView
     */
    private $view;

    /**
     * @var BoxAction
     */
    private $action;

    /**
     * @var string
     */
    private $title;

    public function __construct(
        ProductRepositoryInterface $repository,
        ZettleProductLibraryLinkView $view,
        BoxAction $action,
        string $title
    ) {

        $this->repository = $repository;
        $this->view = $view;
        $this->action = $action;
        $this->title = $title;
    }

    /**
     * @inheritDoc
     */
    public function create_info(string $show_or_save, Entity $entity): BoxInfo
    {
        $boxInfo = new BoxInfo(
            $this->title,
            self::ID,
            BoxInfo::CONTEXT_SIDE,
            BoxInfo::PRIORITY_SORTED
        );

        $boxInfo['uuid'] = (string) $this->repository->findById($entity->id());

        return $boxInfo;
    }

    /**
     * @inheritDoc
     */
    public function accept_post(WP_Post $post, string $save_or_show): bool
    {
        return $this->repository->findById((int) $post->ID) !== null;
    }

    /**
     * @inheritDoc
     */
    public function view_for_post(WP_Post $post): BoxView
    {
        return $this->view;
    }

    /**
     * @inheritDoc
     */
    public function action_for_post(WP_Post $post): BoxAction
    {
        return $this->action;
    }
}
