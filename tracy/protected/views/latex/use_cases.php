<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    "LaTeX",
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'List UseCases', 'url'=>array('useCase/index')),
    );

if ( !$raw )
    echo "<h1>View Generated LaTeX for UseCases</h1>";

function display_uc($useCase, $raw)
{
    
      
    $string = '';
    $id = $useCase->public_id();
    //$actors = implode(', ',$useCase->actors());
    $string .="\\hypertarget{{$id}}{}\n\\subsection{Caso d'uso $id: {$useCase->title}}";
    
      
    if ( isset($useCase->useCaseEvents) && count($useCase->useCaseEvents) )
        $string .="
        \\begin{figure}
            \\centering
            \\includegraphics[scale=0.95]{img/$id.pdf}
            \\caption{Caso d'uso $id: {$useCase->title}}\\label{fig:$id} 
        \\end{figure}";
        
    $string .="\\begin{itemize}\n";
    
    /// @warning HARD CODED DB PK!!!!!!!!!!!
    if ( $useCase->id_use_case != 52 )
        $string .="\\item \\textbf{Attori}: Utente;\n";
    
    $string .="\\item \\textbf{Descrizione}: {$useCase->description}; 
      \\item \\textbf{Precondizione}: {$useCase->pre};\n";
      
    
    $prim = UseCaseEvent::model()->findAll(array(
        'order'=>'`order`',
        'condition'=>'category=1 and use_case=:uc',
        'params'=>array(':uc'=>$useCase->id_use_case)
    ));
    
    if ( count($prim) > 0 )
    {
        $string .="
        \\item \\textbf{Flusso principale degli eventi}:
          \\begin{enumerate}\n";
    
        foreach ( $prim as $event)
        {
            $string .="          \\item {$event->description}";
            if ($event->refers_to)
            {
                $child_id = $event->with('refersTo')->refersTo->public_id();
                $string .=" (\\hyperlink{{$child_id}}{{$child_id}})";
            }
            $string .= ";\n";
        }
        $string .="\n      \\end{enumerate}\n";
    }
    
    $alt = UseCaseEvent::model()->findAll(array(
        'order'=>'`order`',
        'condition'=>'category=3 and use_case=:uc',
        'params'=>array(':uc'=>$useCase->id_use_case)
    ));
    if ( count($alt) )
    {
        $string .="    \\item \\textbf{Estensioni}:
      \\begin{enumerate}\n";
        foreach ( $alt as $event)
        {
            $string .="          \\item {$event->description}";
            if ($event->refers_to)
            {
                $child_id = $event->with('refersTo')->refersTo->public_id();
                $string .=" (\\hyperlink{{$child_id}}{{$child_id}})";
            }
            $string .= ";\n";
        }
        $string .="\n      \\end{enumerate}\n";
    }
    
    $alt = UseCaseEvent::model()->findAll(array(
        'order'=>'`order`',
        'condition'=>'category=4 and use_case=:uc',
        'params'=>array(':uc'=>$useCase->id_use_case)
    ));
    if ( count($alt) )
    {
        $string .="    \\item \\textbf{Inclusioni}:
      \\begin{enumerate}\n";
        foreach ( $alt as $event)
        {
            $string .="          \\item {$event->description}";
            if ($event->refers_to)
            {
                $child_id = $event->with('refersTo')->refersTo->public_id();
                $string .=" (\\hyperlink{{$child_id}}{{$child_id}})";
            }
            $string .= ";\n";
        }
        $string .="\n      \\end{enumerate}\n";
    }
    
    $alt = UseCaseEvent::model()->findAll(array(
        'order'=>'`order`',
        'condition'=>'category=2 and use_case=:uc',
        'params'=>array(':uc'=>$useCase->id_use_case)
    ));
    //$useCase->with(array('useCaseEvents'=>array('condition'=>'category=2','order'=>'order'))); 
    if ( count($alt) )//isset($useCase->useCaseEvents) && count($useCase->useCaseEvents) > 0 )
    {
        $string .="    \\item \\textbf{Scenari Alternativi}:
      \\begin{enumerate}\n";
        foreach ( $alt as $event)
        {
            $string .="          \\item {$event->description}";
            if ($event->refers_to)
            {
                $child_id = $event->with('refersTo')->refersTo->public_id();
                $string .=" (\\hyperlink{{$child_id}}{{$child_id}})";
            }
            $string .= ";\n";
        }
        $string .="\n      \\end{enumerate}\n";
    }
    $string .="    \\item \\textbf{Postcondizione}: {$useCase->post}.\n";
    $string .="  \\end{itemize}\n";
    
    
    if ( $raw )
        echo "$string";
    else
        echo "<pre>$string</pre>";
        
    $useCase->with('useCases');
    if ( isset($useCase->useCases) )
        foreach ( $useCase->useCases as $child)
        {
            display_uc($child,$raw);
        }
}

$sourceArray = array();

$toplevel = UseCase::model()->findAll('parent is null');
foreach ( $toplevel as $useCase)
{
    display_uc($useCase,$raw);
}


  
?> 