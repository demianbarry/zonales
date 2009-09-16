package reservasjavafx.menu;

import javafx.scene.paint.*;
import javafx.scene.input.MouseEvent;

// contiene una opcion del menu
public class menuItem {

    // texto de la opcion
    public var text:String;
    // funcion asociada a la opcion del menu (lo que ejecuta)
    // le traspasa el mouseevent a la funcion; por ejemplo: si el usuario
    // quiere saber donde se hizo click cuando aparecio el menu flotante
    public var call:function(e:MouseEvent):Void;
    // posicion de la opcion en el menu
    public var pos:Number=0;
    // indica que no es un separador de opciones del menu
    protected var isSeparator=false;

    public function equals(other:menuItem):Boolean {
        java.lang.System.out.println("{this.text}---{other.text}");
        return other.text.equals(this.text);
    }


};
