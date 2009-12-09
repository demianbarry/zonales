/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package backend.controllers;

import entities.BaseEntity;
//import org.g2p.tracker.model.entities.WebsiteUsersEntity;
import org.zkoss.zk.ui.Components;
import org.zkoss.zk.ui.Executions;
import org.zkoss.zk.ui.Session;
import org.zkoss.zk.ui.Sessions;
import org.zkoss.zk.ui.ext.AfterCompose;
import org.zkoss.zkplus.databind.DataBinder;
import org.zkoss.zul.Messagebox;
import org.zkoss.zul.Window;

/**
 *
 * @author Administrador
 */
public class BaseController extends Window implements AfterCompose, Constants {

    private static final long serialVersionUID = -6524216859170169468L;

    //ZK databinder
    protected DataBinder binder;
    protected Session session;
    protected boolean pageProtected;

    public BaseController(boolean pageProtected) {
        this.pageProtected = pageProtected;

        if (this.pageProtected && getSession().getAttribute(USER) == null) {
            Executions.sendRedirect("/");
        }

    }

    //operation transient state
    protected BaseEntity _tmpSelected; //store original selected entity
    protected boolean _create; //when new a entity
    protected boolean editMode; //switch to edit mode when doing editing(new/update)
    protected int _lastSelectedIndex = -1; //last selectedIndex before delete

    @Override
    public void afterCompose() {

        //wire variables
        Components.wireVariables(this, this);

        //auto forward
        Components.addForwards(this, this);

    }

    public boolean is_create() {
        return _create;
    }

    public void set_create(boolean _create) {
        this._create = _create;
    }

    public boolean isEditMode() {
        return editMode;
    }

    public boolean is_edit() {
        return !_create;
    }

    public void setEditMode(boolean _editMode) {
        this.editMode = _editMode;
    }

    public int get_lastSelectedIndex() {
        return _lastSelectedIndex;
    }

    public void set_lastSelectedIndex(int _lastSelectedIndex) {
        this._lastSelectedIndex = _lastSelectedIndex;
    }

    public DataBinder getBinder() {
        if (binder == null) {
            binder = (DataBinder) getVariable("binder", false);
        }
        if (binder == null) {
            binder = new DataBinder();
        }
        return binder;
    }

    public void setBinder(DataBinder binder) {
        this.binder = binder;
    }

    protected void showMessage(String msg) {
        showMessage(msg, null);
    }

    protected void showMessage(String msg, Exception ex) {
        try {
            if (ex != null) {
                msg += ex.getMessage();
                ex.printStackTrace();
            }
            Messagebox.show(msg);
        } catch (InterruptedException ex1) {
            System.out.println("ERROR MOSTRANDO MENSAJE: " + ex1.getMessage());
            ex1.printStackTrace();
        }
    }

    public Session getSession() {
        if (session == null) {
            session = Sessions.getCurrent();
        }
        return session;
    }

    public boolean isPageProtected() {
        return pageProtected;
    }

    /*public WebsiteUsersEntity getUserFromSession() {
        return (WebsiteUsersEntity) getSession().getAttribute(USER);
    }

    public String getUserNameFromSession() {
        return (String) getSession().getAttribute(USER_NAME);
    }

    public void setUserInSession(WebsiteUsersEntity user) {
        getSession().setAttribute(USER, user);
    }

    public void setUserNameInSession(String userName) {
        getSession().setAttribute(USER_NAME, userName);
    }

    public HttpServletRequest getHttpRequest() {
        return (HttpServletRequest) getDesktop().getExecution().getNativeRequest();
    }

    public HttpServletResponse getHttpResponse() {
        return (HttpServletResponse) getDesktop().getExecution().getNativeResponse();
    }

    public void gotoHome() {
        ((Include) getDesktop().getAttribute(INCLUDE)).setSrc(HOME_PAGE);
        ((BasePageController) getDesktop().getAttribute(BASE_PAGE_CONTROLLER)).setNavBarItem(HOME_PAGE);
    }*/
}
