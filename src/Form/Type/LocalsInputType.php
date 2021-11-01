<?php


namespace App\Form\Type;


use App\Form\DataTransformer\LocalArrayToStringTransformer;
use App\Repository\LocalRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class LocalsInputType extends AbstractType
{
    private $localRepository;

    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // The Tag collection must be transformed into a comma separated string.
            // We could create a custom transformer to do Collection <-> string in one step,
            // but here we're doing the transformation in two steps (Collection <-> array <-> string)
            // and reuse the existing CollectionToArrayTransformer.
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new LocalArrayToStringTransformer($this->localRepository), true)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['locals'] = $this->localRepository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }
}