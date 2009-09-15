package reservas;

import java.applet.Applet;
import java.lang.reflect.Method;

/**
 *
 * @author Rakesh Menon
 */
public class JavaScriptUtil {

    private Class jsClass = null;
    private Object jsObject = null;

    public JavaScriptUtil(Applet applet) throws Exception {
        
        /**
         * var window = netscape.javascript.JSObject.getWindow(applet);
         * var startDate = window.call("getStartDate", []);
         */
        jsClass = Class.forName("netscape.javascript.JSObject");
        Method getWindow = jsClass.getMethod("getWindow", new Class[] { Applet.class });
        jsObject = getWindow.invoke(null, new Object[] { applet });
    }

    public Object call(String methodName, Object args[]) throws Exception {
        Method call = jsClass.getMethod("call", new Class[] { String.class, Object[].class });
        return call.invoke(jsObject, new Object[] { methodName, args} );
    }
    
    public Object eval(String s) throws Exception {
        Method eval = jsClass.getMethod("eval", new Class[] { String.class });
        return eval.invoke(jsObject, new Object[] { s } );
    }
    
    public Object getMember(String name) throws Exception {
        Method getMember = jsClass.getMethod("getMember", new Class[] { String.class });
        return getMember.invoke(jsObject, new Object[] { name } );
    }

    public Object getSlot(int index) throws Exception {
        Method getSlot = jsClass.getMethod("getSlot", new Class[] { int.class });
        return getSlot.invoke(jsObject, new Object[] { index } );
    }

    public void removeMember(String name) throws Exception {
        Method removeMember = jsClass.getMethod("removeMember", new Class[] { String.class });
        removeMember.invoke(jsObject, new Object[] { name } );
    }

    public void setMember(String name, Object value) throws Exception {
        Method setMember = jsClass.getMethod("setMember", new Class[] { String.class, Object.class });
        setMember.invoke(jsObject, new Object[] { name, value } );
    }

    public void setSlot(int index, Object value) throws Exception {
        Method setSlot = jsClass.getMethod("setSlot", new Class[] { int.class, Object.class });
        setSlot.invoke(jsObject, new Object[] { index, value } );
    }
    
    public String toString() {
        return jsObject.toString();
    }
}
