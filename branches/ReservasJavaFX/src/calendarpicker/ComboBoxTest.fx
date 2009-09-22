/*
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS FILE HEADER
 * Copyright 2009 Sun Microsystems, Inc. All rights reserved. Use is subject to license terms.
 *
 * This file is available and licensed under the following license:
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Sun Microsystems nor the names of its contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
/*
 * http://blogs.sun.com/rakeshmenonp/entry/javafx_combobox
 */

package calendarpicker;

/**
 * @author Rakesh Menon
 */

import javafx.scene.Scene;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.scene.control.TextBox;
import javafx.scene.layout.VBox;
import javafx.geometry.VPos;
import javafx.scene.layout.LayoutInfo;
import javafx.stage.Stage;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;

var nameTextField = TextBox {
    promptText: "Name"
    layoutInfo: LayoutInfo { width: 150 }
};
var nameHBox = HBox {
    content: [
        Label {
            text: "Name"
            layoutInfo: LayoutInfo { width: 80 vpos: VPos.CENTER }
        },
        nameTextField
    ]
    spacing: 5
}

var organizationTextField = TextBox {
    promptText: "Organization"
    layoutInfo: LayoutInfo { width: 200 }
};
var organizationHBox = HBox {
    content: [
        Label {
            text: "Organization"
            layoutInfo: LayoutInfo { width: 80 vpos: VPos.CENTER }
        },
        organizationTextField
    ]
    spacing: 5
}

var productComboBox = ComboBox {
    items: [
        "Operating Systems",
        "Virtualization",
        "Java",
        "Mobile Solutions",
        "Software Infrastructure",
        "Databases",
        "Office Productivity",
        "Systems Management",
        "Developer Tools"
    ]
    layoutInfo: LayoutInfo { width: 150 height: 26 }
};
productComboBox.select(0);
var productHBox = HBox {
    content: [
        Label {
            text: "Products"
            layoutInfo: LayoutInfo { width: 80 vpos: VPos.CENTER }
        },
        productComboBox
    ]
    spacing: 5
}

var selectedProductTextField = TextBox {
    text: bind "{productComboBox.selectedItem}"
    promptText: "Selected"
    layoutInfo: LayoutInfo { width: 200 }
};
var selectedProductHBox = HBox {
    content: [
        Label {
            text: "Selected"
            layoutInfo: LayoutInfo { width: 80 vpos: VPos.CENTER }
        },
        selectedProductTextField
    ]
    spacing: 5
}

var topDownloadsComboBox = ComboBox {
    items: [
        "Java",
        "JavaFX",
        "MySQL Database",
        "Solaris 10 OS",
        "ODF Plugin",
	    "Download Manager",
	    "Studio 12",
	    "Solaris Express DE",
	    "Device Detection Tool",
	    "VirtualBox 2.x",
	    "Common Array Manager"
        "Web Server 7.0"
    ]
    layoutInfo: LayoutInfo { width: 200 height: 26 }
};
topDownloadsComboBox.select(0);

var topDownloadsHBox = HBox {
    content: [
        Label {
            text: "Downloads"
            layoutInfo: LayoutInfo { width: 80 vpos: VPos.CENTER }
        },
        topDownloadsComboBox
    ]
    spacing: 5
}

var vBox = VBox {
    content: [
        nameHBox, organizationHBox,
        productHBox, selectedProductHBox,
        topDownloadsHBox
    ]
    spacing: 5
    layoutX: 15
    layoutY: 15
}

var bgRect : Rectangle = Rectangle {
    width: bind bgRect.scene.width - 10
    height: bind bgRect.scene.height - 10
    x: 5
    y: 5
    arcWidth: 5
    arcHeight: 5
    strokeWidth: 2.0
    fill: Color.ALICEBLUE
    stroke: Color.AQUAMARINE
}

Stage {
    title: "JavaFX - ComboBox"
    scene: Scene {
        content: [ bgRect, vBox ]
        width: 320
        height: 320
    }
}

