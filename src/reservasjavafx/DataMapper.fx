/*
 * DataMapper.fx
 *
 * Created on 01-oct-2009, 17:11:25
 */

package reservasjavafx;

/**
 * @author g2p
 */

import javafx.data.pull.PullParser;
import java.io.ByteArrayInputStream;
import javafx.data.pull.Event;
import javafx.reflect.FXObjectValue;
import javafx.reflect.FXLocal.Context;
import javafx.reflect.FXLocal;
import javafx.reflect.FXLocal.ClassType ;
import javafx.reflect.FXVarMember;
import java.util.List;
import java.lang.StringBuffer;


/**
 * @author Tushar.Kale@w3aps.com
 */

class Person {
    var firstName:String;
    var lastName:String;
    var age: Integer;
    var isStudent : Boolean;
}

class Mapper {

    var dataString : String;   //JSON data
    var fxObject : Object; // JavafX object

    //variagles for using reflection
    var local: FXLocal = new FXLocal();
    var context: Context = local.getContext();
    var fxVarMember: FXVarMember;
    var localClassType: ClassType;
    var objectValue: FXObjectValue;

    var bin : ByteArrayInputStream;  //For PullParser


    function initFx(): Void
    {
        bin = new ByteArrayInputStream(dataString.getBytes());
        localClassType = context.makeClassRef(fxObject.getClass());
        objectValue = context.mirrorOf(fxObject);


        def parser = PullParser {
        documentType: PullParser.JSON;
        input: bin;
        onEvent: function(event: Event) {
        if (event.type == PullParser.START_VALUE) {
            fxVarMember =  localClassType.getVariable(event.name);
        } else if (event.type == PullParser.TEXT) {
            fxVarMember.setValue(objectValue, context.mirrorOf(event.text));
        } else if (event.type == PullParser.INTEGER) {
            fxVarMember.setValue(objectValue, context.mirrorOf(event.integerValue));
        } else if (event.type == PullParser.TRUE) {
            fxVarMember.setValue(objectValue, context.mirrorOf(event.booleanValue));
        } else if (event.type == PullParser.NUMBER) {
            fxVarMember.setValue(objectValue, context.mirrorOf(event.numberValue));
        }
        }
    }
    parser.parse();
    }

function toJson() : String  {

        var varName: String;
        var varValue: String;
        var varType: String;  //type of variable Integer, String etc

        var t: ClassType = context.makeClassRef(fxObject.getClass());
        var ov: FXLocal.ObjectValue = new FXLocal.ObjectValue(fxObject,  context) ;
        var variables: List = t.getVariables(true);

        var sb: StringBuffer = new StringBuffer("\{");  //Beginning of JSON object
        try {
        for (item in variables) {
            fxVarMember = item as FXVarMember;
            varName = fxVarMember.getName();
            varType = fxVarMember.getType().toString();

            println("name={varName}  type = {varType}");

            if (indexof item > 0)
            {
                sb.append(", "); //Don't want comma for the first entry but need after every variable
            }
            sb.append(varName).append(": ");
            if(varType.indexOf("java.lang.String") >= 0)
            {  //put double quotes
                varValue = fxVarMember.getValue(ov).getValueString();
                sb.append("\"").append(varValue).append("\"");
            } else {
                    varValue = fxVarMember.getValue(ov).getValueString();
                    sb.append(varValue);
            }
        }
         sb.append("\}");

        } catch ( ex : java.lang.NullPointerException ) {
            println("null  pointer detected!");
            throw ex;
        }

        return sb.toString();
    }
}

