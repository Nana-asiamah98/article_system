<?php


namespace App\Form;


use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserSelectTextType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RouterInterface
     */
    private $routerInterface;


    /**
     * UserSelectTextType constructor.
     * @param UserRepository $userRepository
     * @param RouterInterface $routerInterface
     */
    public function __construct(UserRepository $userRepository, RouterInterface $routerInterface)
    {
        $this->userRepository = $userRepository;
        $this->routerInterface = $routerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer(
            $this->userRepository,
            $options['finder_callback']
        ));
    }


    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        return $resolver->setDefaults([
            'invalid_message' => "Wrong Email",
            'finder_callback' => function( UserRepository $userRepository, string $email){
                return  $userRepository->findOneBy(['email'=>$email]);

            }
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ': '';
        $class .= 'js-user-complete';

        $attr['class'] = $class;
        $attr['data-complete-url'] = $this->routerInterface->generate('admin_utility_users');
        $view->vars['attr'] = $attr;

    }


}
