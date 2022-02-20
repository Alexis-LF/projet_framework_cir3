<?php
/**
 * Formulaire de l'entité Zone
 *
 * @package    Projet Framework
 * @author     Alexis Le Floch <alexis.le-floch@isen-ouest.yncrea.fr> , Noam Nedelec-Salmon <noam.nedelec-salmon@isen-ouest.yncrea.fr>
 * @version    1.0 
 */

namespace App\Form;

use App\Entity\Zone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
        ]);
    }
}
