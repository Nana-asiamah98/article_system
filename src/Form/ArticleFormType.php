<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * ArticleFormType constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();


        $builder->add('title', null, [
            'required' => false
        ])
            ->add('content')
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit
            ]);
        if ($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                'widget' => 'single_text'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }


}
