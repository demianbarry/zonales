/*
 * Main.fx
 *
 * Created on 01/09/2009, 03:46:19 PM
 */

package reservas;

import javafx.stage.Stage;
import javafx.scene.Scene;

/**
 * @author Administrador
 */

Stage {
  title: "Layout para Teatros"
  width: 250
  height: 450
  onClose: function() {
       java.lang.System.exit( 0 );
  }
  visible: true

  scene: Scene {
     content: AnfiteatroLayout {
             
     }
  }
}