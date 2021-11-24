<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
        /** @var Article/null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $location = $article ? $article->getLocation() : null;


        $builder->add('title', null, [
            'required' => false
        ])
            ->add('content')
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit
            ])
            ->add('location',ChoiceType::class,[
                'choices'=>[
                    'The Solar System' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space'
                ],
                'attr' => [
                    'data-specific-location' => '/admin/article/location-select',
                    'class' => 'js-article-form-location'
                ],
                'required' => false,
                'placeholder' => 'Where exactly'
            ]);

        if($location){
            $builder->add('specificLocationName',ChoiceType::class,[
                'choices'=> $this->getLocationNameChoices($location),
                'required'=>false,
                'placeholder' => 'Select specific location',
                'attr' => [
                    'class' => 'js-specific-location-target'
                ]

            ]);
        }


        if ($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                'widget' => 'single_text'
            ]);
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function(FormEvent $event){
            /** @var Article|null $data */
                $data = $event->getData();

                if(!$data){
                    return;
                }

                $this->setupSpecificLocation(
                    $event->getForm(),
                    $data->getLocation()
                );
            }
        );

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event){
                $form = $event->getForm();
                $this->setupSpecificLocation(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }


    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];

        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];

        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationNameChoices[$location] ?? null;
    }

    private function setupSpecificLocation(FormInterface $form,?string $location){
        if(null === $location){
            $form->remove('specificLocationName');
            return;
        }

        $choices = $this->getLocationNameChoices($location);

        if(null === $choices){
            $form->remove('specificLocationName');
            return;
        }

        $form->add('specificLocationName',ChoiceType::class,[
            'choices'=> $choices,
            'required'=>false,
            'placeholder' => 'Where Exactly'

        ]);
    }



}
