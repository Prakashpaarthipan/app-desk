Imports TCS_Centra
Imports TCS_Centra.LoginForm
Imports TCS_Centra.Fetch
Imports TCS_Centra.MainForm
Imports Infragistics.Shared
Imports Infragistics.Win
Imports Infragistics.Win.UltraWinGrid
Imports Infragistics.Win.UltraWinEditors
Imports System
Imports System.Net
Imports System.IO

Public Class Target_Request_Approval_Form
    Inherits TCS_Centra.TCSForm
    Dim D_Data As New D_Data.Service
    Private CData As CData.CData
    Private dsTRA As CData.TARGET_REQUEST_APPROVAL_Dataset
    Private dsBTT As CData.BRANCH_TRANSFER_TARGET_Dataset
    Private dsRTT As CData.RETURN_TARGET_Dataset
    Private dsAPT As CData.APPRX_PURCHASE_TARGET_Dataset
    Private dsitm As D_Data.TARGET_REQUEST_ITEM_DataSet

    Private dvTRA, dvSUM, dvBTT, dvRTT, dv, dvAPP, dvAPT, dvitm, dvitm_sav As DataView
    Private drvTRA, drvSUM, drvBTT, drvRTT, drv, drv1, drvAPT, drvitm, drvitm_sav As DataRowView
    Private Viptime As String = ""
    Dim EmpStr As String = ""
    Dim LMode As String
    Dim bcode, ecode, dcode, toecode, todcode As Integer
    Private dvPURINVN As DataView
    Private dsRET As DataSet
    Dim RetAmnt As Decimal = 0
    Dim PURYEAR As String = ""
    Dim purcono As Integer
    Dim Convert As Char
    Dim MIS As Char = "N"
    Dim GM As Char = "N"
    Dim itm_save As Boolean = False
    Dim dvitmscn As New DataView
    Dim Mtr_item As Boolean = False
    Dim mtr_item1 As Boolean = False
    Dim DVSUP As DataView
    Dim dv_SGroup As DataView

    Private img, img_new As Byte()
    Dim Appl_path As String = ""
    Dim img1 As Byte()
    Dim img2 As Byte()
    Dim ImgSelect As Boolean = False


    Public Function Target_Request_Approval_Form_New() As Boolean
        Try
            LMode = "NEW"
            CData = New CData.CData
            D_Data = New D_Data.Service
            dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
            dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

            dsitm = New D_Data.TARGET_REQUEST_ITEM_DataSet
            dvitm_sav = New DataView(dsitm.TARGET_REQUEST_ITEM)

            TARMODE_UltraComboEditor.Enabled = True
            REQSRNO_TextEditor.Enabled = True
            RESCODE_ComboEditor.Enabled = True
            REMA_TextEditor.Enabled = True
            SECCODE_ComboEditor.Enabled = True
            Branch_UltraComboEditor.Enabled = True
            TARMODE_UltraComboEditor.SelectedIndex = 0
            REQUEST_TabControl.Tabs(0).Selected = True
            STAT_ENTMODE_ComboEditor.Enabled = True
            REFYEAR_TextBox.Enabled = True
            REFNUMB_TextBox.Enabled = True
            LOAD_Button.Enabled = True
            RTTVALU_TextBox.Enabled = True
            RTTQNTY_TextBox.Enabled = True
            RTTVALU_TextBox.ReadOnly = False
            TODETL_UltraLabel.Text = ""
            TRNSUP_UltraLabel.Text = ""
            PMname_UltraCombo.Enabled = True
            TRNSUP_UltraLabel.Visible = False
            QTY_Label.Text = ""
            Dim dv As New DataView
            'dv = TCS_Lib.Get_Cmd_View("Select Nonpurn from Codeinc")
            'If TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 14 And dv(0)("NONPURN") = 1 Then
            '    WOS_CheckBox.Visible = True
            'End If

            'dv = New DataView
            'dv = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode=" & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)))
            'BRANCH_UltraLabel.Text = IIf(dv(0).Item("BRNCODE") = 888, dv(0).Item("BRNNAME") & "- CORP", dv(0).Item("BRNNAME"))


            Dim branch_ As String
            Dim dv_branch As New DataView
            dv_branch = TCS_Lib.Get_Cmd_View("select * from tcs_centra_attn where usrcode=" & MainForm.UserID & "")

            If dv_branch.Count > 0 Then

                If dv_branch(0)("MULTIBRN") > 0 Then
                    branch_ = dv_branch(0)("MULTIBRN")
                Else
                    branch_ = IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID))
                End If
            Else
                branch_ = IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID))
            End If
            Super_bazar_CheckBox.Checked = False
            Super_bazar_CheckBox.Visible = False

            Branch_UltraComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode in (" & branch_ & ")")
            Branch_UltraComboEditor.DisplayMember = "BRNNAME"
            Branch_UltraComboEditor.ValueMember = "BRNCODE"
            Branch_UltraComboEditor.SelectedIndex = 0

            SECCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select seccode,secname from section where deleted='N' order by seccode")
            SECCODE_ComboEditor.DisplayMember = "SECNAME"
            SECCODE_ComboEditor.ValueMember = "SECCODE"
            SECCODE_ComboEditor.SelectedIndex = 0

            MIS_CheckBox.Enabled = True
            'MIS_CheckBox.Checked = False
            TOEMPSRNO_TextEditor.Clear()
            TOEMPSRNO_TextEditor.Enabled = True
            'TOEMPSRNO_TextEditor.Focus()
            TARMODE_UltraComboEditor.Focus()



            Return True
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    Public Function Target_Request_Approval_Form_Cancel() As Boolean
        Try
            TARMODE_UltraComboEditor.Enabled = False
            REQSRNO_TextEditor.Enabled = False
            RESCODE_ComboEditor.Enabled = False
            REMA_TextEditor.Enabled = False
            SECCODE_ComboEditor.Enabled = False
            TOBRNCODE_ComboBox.Enabled = False
            BUNYEAR_TextBox.Enabled = False
            BUNNUMB_TextBox.Enabled = False
            REQDETL_UltraLabel.Text = ""
            REQDETL_UltraLabel.Tag = 0
            TARMODE_UltraComboEditor.ReadOnly = False
            TOEMPSRNO_TextEditor.Tag = 0
            TOEMPSRNO_TextEditor.Text = ""
            TODETL_UltraLabel.Text = ""
            MIS_CheckBox.Enabled = False
            TOEMPSRNO_TextEditor.Enabled = False
            TRNSUP_UltraLabel.Text = ""
            TRNSUP_UltraLabel.Visible = False
            REQSRNO_TextEditor.Tag = 0
            REQSRNO_TextEditor.Clear()
            REMA_TextEditor.Clear()
            APP_DETAIL_Label.Text = ""
            BUNYEAR_TextBox.Clear()
            BUNNUMB_TextBox.Clear()
            SUPCODE_UltraTextEditor.Text = ""
            ENTSTAT_ComboEditor.Enabled = False
            APPREMA_TextEditor.Enabled = False
            APPREMA_TextEditor.Clear()
            SUMMARY_UltraGrid.DataSource = Nothing
            RTTVALU_TextBox.Enabled = False
            RTTQNTY_TextBox.Enabled = False
            RTTQNTY_TextBox.Clear()
            RTTVALU_TextBox.Clear()
            STAT_ENTMODE_ComboEditor.SelectedIndex = 0
            STAT_ENTMODE_ComboEditor.Enabled = False
            REFYEAR_TextBox.Clear()
            REFNUMB_TextBox.Clear()
            REFYEAR_TextBox.Enabled = False
            REFNUMB_TextBox.Enabled = False
            LOAD_Button.Enabled = False
            REQSTATUS_Grid.DataSource = Nothing
            PMname_UltraCombo.DataSource = Nothing
            PMname_UltraCombo.Enabled = False
            MIS_CheckBox.Checked = False
            BRANCH_UltraLabel.Text = ""
            Branch_UltraComboEditor.DataSource = Nothing
            bcode = 0
            ecode = 0
            dcode = 0
            toecode = 0
            todcode = 0
            MIS = "N"
            Mnual_GroupBox.Enabled = False
            Mnual_GroupBox.Visible = False
            Itmcode_TextEditor.Text = ""
            Detail_UltraGrid.DataSource = Nothing
            RTTVALU_TextBox.ReadOnly = False
            TVAl_Label.Text = ""
            RTTVALU_TextBox.Text = ""
            RTTQNTY_TextBox.Text = ""
            Branch_UltraComboEditor.Enabled = False
            Super_bazar_CheckBox.Checked = False
            Super_bazar_CheckBox.Visible = False
            If LMode = "FIND" Then
                Timer1.Start()
                Return False
            Else
                Return True
            End If
           
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    'Public Function Target_Request_Approval_Form_Find() As Boolean
    '    Try
    '        Convert = "N"
    '        MIS = "N"
    '        MIS_CheckBox.Enabled = False
    '        ENTSTAT_ComboEditor.Enabled = True
    '        ENTSTAT_ComboEditor.SelectedIndex = 0
    '        APPREMA_TextEditor.Enabled = True

    '        LMode = "FIND"
    '        CData = New CData.CData
    '        dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
    '        dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

    '        REQUEST_TabControl.Tabs(1).Selected = True

    '        SECCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select seccode,secname from section where deleted='N' order by seccode")
    '        SECCODE_ComboEditor.DisplayMember = "SECNAME"
    '        SECCODE_ComboEditor.ValueMember = "SECCODE"
    '        SECCODE_ComboEditor.SelectedIndex = 0

    '        dv = New DataView
    '        dv = TCS_Lib.Get_Cmd_View("select count(*) cnt from employee_office emp,userid usr where emp.esecode=47 and usr.empsrno=emp.empsrno and usr.usrcode=" & MainForm.UserID)
    '        If dv(0).Item("CNT") > 0 Then
    '            MIS = "Y"
    '        Else
    '            MIS = "N"
    '        End If

    '        dvSUM = New DataView
    '        If dv(0).Item("CNT") > 0 Then
    '            dvSUM = TCS_Lib.Get_Cmd_View("select substr(brn.nicname,3,3) brnname,req.brncode,req.refyear,req.refnumb,req.refsrno,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice','Approximate Purchase'))) tarmode,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department " & _
    '                  " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
    '                  " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
    '                  " req.brncode=brn.brncode and req.to_dept='MIS'")
    '        Else
    '            dvSUM = TCS_Lib.Get_Cmd_View("select substr(brn.nicname,3,3) brnname,req.brncode,req.refyear,req.refnumb,req.refsrno,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice','Approximate Purchase'))) tarmode,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department " & _
    '                   " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
    '                   " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
    '                   " req.brncode=brn.brncode and (req.to_empsrno in (select empsrno from userid where usrcode=" & MainForm.UserID & "))")
    '        End If

    '        If dv(0).Item("CNT") > 0 Then
    '            dvSUM.RowFilter = "DEPARTMENT='MIS'"
    '        End If

    '        SUMMARY_UltraGrid.DataSource = dvSUM
    '        SUMMARY_UltraGrid.DataBind()

    '        dvSUM.Table.Columns("BRNNAME").ReadOnly = True
    '        dvSUM.Table.Columns("TARMODE").ReadOnly = True
    '        dvSUM.Table.Columns("EMPNAME").ReadOnly = True
    '        dvSUM.Table.Columns("SECNAME").ReadOnly = True
    '        dvSUM.Table.Columns("RTTVALUE").ReadOnly = True
    '        dvSUM.Table.Columns("ENT_REMARK").ReadOnly = True
    '        SUMMARY_UltraGrid.Focus()
    '        Timer1.Start()
    '        Return True
    '    Catch ex As Exception
    '        MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    End Try
    'End Function

    Public Function Target_Request_Approval_Form_Find() As Boolean
        Try
            Convert = "N"
            MIS = "N"
            MIS_CheckBox.Enabled = False
            ENTSTAT_ComboEditor.Enabled = True
            ENTSTAT_ComboEditor.SelectedIndex = 0
            APPREMA_TextEditor.Enabled = True

            LMode = "FIND"
            CData = New CData.CData
            dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
            dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

            REQUEST_TabControl.Tabs(1).Selected = True

            SECCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select seccode,secname from section where deleted='N' order by seccode")
            SECCODE_ComboEditor.DisplayMember = "SECNAME"
            SECCODE_ComboEditor.ValueMember = "SECCODE"
            SECCODE_ComboEditor.SelectedIndex = 0

            dv = New DataView
            'dv = TCS_Lib.Get_Cmd_View("select count(*) cnt from employee_office emp,userid usr where emp.esecode in(47,96) and usr.empsrno=emp.empsrno and usr.usrcode=" & MainForm.UserID)
            dv = TCS_Lib.Get_Cmd_View("select target_req_empsec(" & MainForm.UserID & ",'TARGET_EMPSEC') as TAR_EMPSEC from dual")
            MIS = dv(0)("tar_empsec")

            Dim dv1 As DataView

            dv1 = TCS_Lib.Get_Cmd_View("select * from employee_office emp,userid usr where usr.empsrno=emp.empsrno and emp.descode in (19,165) and usr.usrcode=" & MainForm.UserID)
            If dv1.Count > 0 Then
                GM = "Y"
            Else
                GM = "N"
            End If
            'If dv(0).Item("CNT") > 0 Then
            '    MIS = "Y"
            'Else
            '    MIS = "N"
            'End If

            dvSUM = New DataView
            'If dv(0).Item("CNT") > 0 Then
            '    dvSUM = TCS_Lib.Get_Cmd_View("select trunc(req.adddate) entdate,substr(brn.nicname,3,3) brnname,req.brncode,req.refyear,req.refnumb,req.refsrno,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice','Approximate Purchase'))) tarmode,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,null,'-',req.bunyear||' '||req.bunnumb) bunnumb " & _
            '          " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
            '          " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
            '          " req.brncode=brn.brncode and req.to_dept='MIS'")
            'Else
            '    dvSUM = TCS_Lib.Get_Cmd_View("select trunc(req.adddate) entdate,substr(brn.nicname,3,3) brnname,req.brncode,req.refyear,req.refnumb,req.refsrno,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice','Approximate Purchase'))) tarmode,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,null,'-',req.bunyear||' '||req.bunnumb) bunnumb " & _
            '           " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
            '           " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
            '           " req.brncode=brn.brncode and (req.to_empsrno in (select empsrno from userid where usrcode=" & MainForm.UserID & "))")
            'End If
            If MIS = "Y" Then
                'dvSUM = TCS_Lib.Get_Cmd_View("select req.refyear,trunc(req.adddate) entdate,req.refnumb,sup.supname,cty.ctyname,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice',decode(req.tarmode,'T','Transport Debit','Approximate Purchase')))) tarmode,substr(brn.nicname,3,3) brnname,req.brncode,req.refsrno,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,0,'-',decode(req.bunnumb,null,'-',req.bunyear||' '||req.bunnumb))||decode(req.bunnumb,0,null,decode(req.tarmode,'R','(PAC)',decode(req.tarmode,'M','(PJV)',decode(req.tarmode,'B','','')))) bunnumb " & _
                '      " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn,city cty  " & _
                '      " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
                '      " req.brncode=brn.brncode and sup.ctycode=cty.ctycode and req.to_dept='MIS'")
                dvSUM = TCS_Lib.Get_Cmd_View("select req.refyear,trunc(req.adddate) entdate,req.supcode,req.refnumb,sup.supname,cty.ctyname,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice',decode(req.tarmode,'T','Transport Debit','Approximate Purchase')))) tarmode, " & _
                                           " substr(brn.nicname,3,3) brnname,req.brncode,req.refsrno,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,0,'-',decode(req.bunnumb,null,'-', " & _
                                           " req.bunyear||' '||req.bunnumb))||decode(req.bunnumb,0,null,decode(req.tarmode,'R','(PAC)',decode(req.tarmode,'M','(PJV)',decode(req.tarmode,'B','','')))) bunnumb  from trandata.target_request_approval@tcscentr req,trandata.employee_office@tcscentr emp,trandata.empsection@tcscentr ese,trandata.designation@tcscentr des, " & _
                                           " trandata.supplier@tcscentr sup,trandata.section@tcscentr sec,trandata.branch@tcscentr brn,trandata.city@tcscentr cty   where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and req.tarmode<>'T' and  " & _
                                           " req.brncode=brn.brncode and req.to_dept='MIS' and sup.ctycode=cty.ctycode(+) union " & _
                                           " select req.refyear,trunc(req.adddate) entdate,req.supcode,req.refnumb,sup.supname,cty.ctyname,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice',decode(req.tarmode,'T','Transport Debit','Approximate Purchase')))) tarmode, " & _
                                           " substr(brn.nicname,3,3) brnname,req.brncode,req.refsrno,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,0,'-',decode(req.bunnumb,null,'-', " & _
                                           " req.bunyear||' '||req.bunnumb))||decode(req.bunnumb,0,null,decode(req.tarmode,'R','(PAC)',decode(req.tarmode,'M','(PJV)',decode(req.tarmode,'B','','')))) bunnumb  from trandata.target_request_approval@tcscentr req,trandata.employee_office@tcscentr emp,trandata.empsection@tcscentr ese,trandata.designation@tcscentr des, " & _
                                           " trandata.supplier_asset@tcscentr sup,trandata.section@tcscentr sec,trandata.branch@tcscentr brn,trandata.city@tcscentr cty   where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and req.tarmode='T' and  " & _
                                           " req.brncode=brn.brncode and req.to_dept='MIS' and sup.ctycode=cty.ctycode(+) ")

            Else
                'dvSUM = TCS_Lib.Get_Cmd_View("select req.refyear,trunc(req.adddate) entdate,substr(brn.nicname,3,3) brnname,req.refnumb,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice','Approximate Purchase'))) tarmode,req.brncode,req.refsrno,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,0,'-',decode(req.bunnumb,null,'-',req.bunyear||' '||req.bunnumb))||decode(req.bunnumb,0,null,decode(req.tarmode,'R','(PAC)',decode(req.tarmode,'M','(PJV)',decode(req.tarmode,'B','','')))) bunnumb " & _
                '       " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
                '       " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
                '       " req.brncode=brn.brncode and (req.to_empsrno in (select empsrno from userid where usrcode=" & MainForm.UserID & "))")

                dvSUM = TCS_Lib.Get_Cmd_View("select req.refyear,trunc(req.adddate) entdate,substr(brn.nicname,3,3) brnname,To_Number(req.refnumb) refnumb,decode(req.tarmode,'R','Return Slip',decode(req.tarmode,'M','Manual Return Slip',decode(req.tarmode,'B','Branch Invoice',decode(req.tarmode,'T','Transport Debit','Approximate Purchase')))) tarmode,req.brncode,req.refsrno,req.empcode||'-'||req.empname empname,ese.esename,des.desname,sec.secname,req.rttvalue,req.ent_remark,nvl(req.to_dept,'-') department,decode(req.bunnumb,0,'-',decode(req.bunnumb,null,'-',req.bunyear||' '||req.bunnumb))||decode(req.bunnumb,0,null,decode(req.tarmode,'R','(PAC)',decode(req.tarmode,'M','(PJV)',decode(req.tarmode,'B','','')))) bunnumb " & _
                        " from target_request_approval req,employee_office emp,empsection ese,designation des,supplier sup,section sec,branch brn  " & _
                        " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And req.msgstat='S' and req.supcode=sup.supcode(+) and req.seccode=sec.seccode(+) and  " & _
                        " req.brncode=brn.brncode and (req.to_empsrno in (select empsrno from userid where usrcode=" & MainForm.UserID & ")) union " & _
                        " select req.entyear refyear,trunc(sysdate) entdate,substr(brn.nicname,3,3) brnname,req.entnumb refnumb,'Supplier Sample' tarmode,req.brncode,'1' refsrno,emp.empcode||'-'||emp.empname empname,ese.esename,des.desname,sec.secname secname,req.SAMVALU,sup.supcode||'-'||sup.supname ent_remark,'0' department, '' bunnumb  " & _
                        " from Supplier_Sample_Issue req,employee_office emp,empsection ese,designation des, branch brn ,Manual_Return_slip_summary man ,supplier sup ,section sec " & _
                        " where req.deleted='N' and req.purempc = emp.empsrno And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) And  " & _
                        " req.brncode=brn.brncode AND req.brncode=man.brncode and req.mrtyear=man.MRTyear and req.MRTNUMB=man.MRTNUMB and  man.supcode=sup.supcode and req.seccode=sec.seccode and   req.STATUS in ('N','P') and (req.purempc in (select empsrno from userid where usrcode=" & MainForm.UserID & ")) ")
            End If

            If MIS = "Y" Then
                dvSUM.RowFilter = "DEPARTMENT='MIS'"
            End If

            SUMMARY_UltraGrid.DataSource = dvSUM
            SUMMARY_UltraGrid.DataBind()

            'dvSUM.Table.Columns("ENTDATE").ReadOnly = True
            'dvSUM.Table.Columns("BRNNAME").ReadOnly = True
            'dvSUM.Table.Columns("REFYEAR").ReadOnly = True
            'dvSUM.Table.Columns("TARMODE").ReadOnly = True
            'dvSUM.Table.Columns("EMPNAME").ReadOnly = True
            'dvSUM.Table.Columns("SECNAME").ReadOnly = True
            'dvSUM.Table.Columns("RTTVALUE").ReadOnly = True
            'dvSUM.Table.Columns("ENT_REMARK").ReadOnly = True
            'dvSUM.Table.Columns("BUNNUMB").ReadOnly = True
            If MIS = "Y" Then
                dvSUM.Table.Columns("supname").ReadOnly = True
                dvSUM.Table.Columns("ctyname").ReadOnly = True
            Else
               
            End If
            dvSUM.Table.Columns("REFYEAR").ReadOnly = True
            dvSUM.Table.Columns("ENTDATE").ReadOnly = True
            dvSUM.Table.Columns("REFNUMB").ReadOnly = True
            dvSUM.Table.Columns("TARMODE").ReadOnly = True
            dvSUM.Table.Columns("BRNNAME").ReadOnly = True
            dvSUM.Table.Columns("EMPNAME").ReadOnly = True
            dvSUM.Table.Columns("SECNAME").ReadOnly = True
            dvSUM.Table.Columns("RTTVALUE").ReadOnly = True
            dvSUM.Table.Columns("ENT_REMARK").ReadOnly = True
            dvSUM.Table.Columns("BUNNUMB").ReadOnly = True

            SUMMARY_UltraGrid.Focus()
            Timer1.Start()
            Super_bazar_CheckBox.Checked = False
            Super_bazar_CheckBox.Visible = False
            Return True
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    Private Sub SUMMARY_UltraGrid_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles SUMMARY_UltraGrid.DoubleClick
        If dvSUM.Count > 0 Then
            Timer1.Stop()
            APP_DETAIL_Label.Text = "" & vbCrLf & vbCrLf

            drv1 = dvSUM(SUMMARY_UltraGrid.ActiveRow.Index)

            dvAPP = New DataView
            If Mid(drv1("TARMODE"), 1, 1) = "R" Or Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "T" Or Mid(drv1("TARMODE"), 1, 1) = "E" Then
                dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,REQ.REFSRNO,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.RESNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,RET_REASON MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.RESCODE ORDER BY REQ.REFSRNO")
            ElseIf Mid(drv1("TARMODE"), 1, 1) = "A" Then
                dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,APPRX_PURCHASE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.APTMODE")
            ElseIf Mid(drv1("TARMODE"), 1, 1) = "S" Then
                dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.PUREMPC,DES.DESSRNO,REQ.PUREMPC,EMP.EMPNAME,'SAMPLE' REMARK ,'SAMPLE' REANAME FROM SUPPLIER_SAMPLE_ISSUE REQ ,EMPLOYEE_OFFICE EMP, DESIGNATION DES WHERE REQ.PUREMPC=EMP.EMPSRNO AND  EMP.DESCODE=DES.DESCODE AND REQ.ENTYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.ENTNUMB)=" & drv1("REFNUMB") & " ")
            Else
                dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODENAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,INVOICE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.MODECODE")
            End If
            APP_DETAIL_Label.Text &= dvAPP(0).Item("REANAME") & vbCrLf & vbCrLf

            For Each drv In dvAPP
                APP_DETAIL_Label.Text &= drv("EMPNAME") & " - " & drv("REMARK") & vbCrLf & vbCrLf
            Next
            If Mid(drv1("TARMODE"), 1, 1) <> "S" Then

                dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
                dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, drv1("REFYEAR"), drv1("REFNUMB"), 1)
                dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
                If dvTRA.Count > 0 Then
                    drvTRA = dvTRA(0)

                    DES_ComboEditor.Enabled = True
                    APPREMA_TextEditor.Enabled = True
                    ENTSTAT_ComboEditor.Enabled = True
                    ENTSTAT_ComboEditor.SelectedIndex = 0
                    Display_Data()
                End If
            End If
            DES_ComboEditor.Focus()
        End If
    End Sub

    'Private Sub SUMMARY_UltraGrid_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles SUMMARY_UltraGrid.InitializeLayout
    '    SUMMARY_UltraGrid.DisplayLayout.Override.CellClickAction = CellClickAction.RowSelect
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectTypeRow = SelectType.Single
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.FontData.Bold = DefaultableBoolean.True
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.ForeColor = Color.Black

    '    e.Layout.Bands(0).Columns("BRNCODE").Hidden = True
    '    e.Layout.Bands(0).Columns("REFYEAR").Hidden = True
    '    e.Layout.Bands(0).Columns("REFNUMB").Hidden = True
    '    e.Layout.Bands(0).Columns("REFSRNO").Hidden = True
    '    e.Layout.Bands(0).Columns("esename").Hidden = True
    '    e.Layout.Bands(0).Columns("desname").Hidden = True
    '    e.Layout.Bands(0).Columns("department").Hidden = True

    '    e.Layout.Bands(0).Columns("BRNNAME").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("TARMODE").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("EMPNAME").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("SECNAME").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("RTTVALUE").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("ENT_REMARK").CellAppearance.TextHAlign = HAlign.Center

    '    e.Layout.Bands(0).Columns("BRNNAME").Header.Caption = "Branch"
    '    e.Layout.Bands(0).Columns("TARMODE").Header.Caption = "Mode"
    '    e.Layout.Bands(0).Columns("EMPNAME").Header.Caption = "Employee Name"
    '    e.Layout.Bands(0).Columns("SECNAME").Header.Caption = "Section"
    '    e.Layout.Bands(0).Columns("RTTVALUE").Header.Caption = "Value"
    '    e.Layout.Bands(0).Columns("ENT_REMARK").Header.Caption = "Remark"

    '    e.Layout.Bands(0).Columns("BRNNAME").Width = 75
    '    e.Layout.Bands(0).Columns("TARMODE").Width = 150
    '    e.Layout.Bands(0).Columns("EMPNAME").Width = 250
    '    e.Layout.Bands(0).Columns("SECNAME").Width = 150
    '    e.Layout.Bands(0).Columns("RTTVALUE").Width = 150
    '    e.Layout.Bands(0).Columns("ENT_REMARK").Width = 250

    '    e.Layout.Bands(0).Columns("BRNNAME").FilterOperandStyle = FilterOperandStyle.Combo
    'End Sub

    'Private Sub SUMMARY_UltraGrid_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles SUMMARY_UltraGrid.InitializeLayout
    '    SUMMARY_UltraGrid.DisplayLayout.Override.CellClickAction = CellClickAction.RowSelect
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectTypeRow = SelectType.Single
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.FontData.Bold = DefaultableBoolean.True
    '    SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.ForeColor = Color.Black

    '    e.Layout.Bands(0).Columns("BRNCODE").Hidden = True
    '    'e.Layout.Bands(0).Columns("REFYEAR").Hidden = True
    '    e.Layout.Bands(0).Columns("REFNUMB").Hidden = True
    '    e.Layout.Bands(0).Columns("REFSRNO").Hidden = True
    '    e.Layout.Bands(0).Columns("esename").Hidden = True
    '    e.Layout.Bands(0).Columns("desname").Hidden = True
    '    e.Layout.Bands(0).Columns("department").Hidden = True

    '    e.Layout.Bands(0).Columns("ENTDATE").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("BRNNAME").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("REFYEAR").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("TARMODE").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("EMPNAME").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("SECNAME").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("RTTVALUE").CellAppearance.TextHAlign = HAlign.Left
    '    e.Layout.Bands(0).Columns("ENT_REMARK").CellAppearance.TextHAlign = HAlign.Center
    '    e.Layout.Bands(0).Columns("BUNNUMB").CellAppearance.TextHAlign = HAlign.Center

    '    e.Layout.Bands(0).Columns("ENTDATE").Header.Caption = "Ent.Date"
    '    e.Layout.Bands(0).Columns("BRNNAME").Header.Caption = "Branch"
    '    e.Layout.Bands(0).Columns("REFYEAR").Header.Caption = "Ac.Year"
    '    e.Layout.Bands(0).Columns("TARMODE").Header.Caption = "Mode"
    '    e.Layout.Bands(0).Columns("EMPNAME").Header.Caption = "Employee Name"
    '    e.Layout.Bands(0).Columns("SECNAME").Header.Caption = "Section"
    '    e.Layout.Bands(0).Columns("RTTVALUE").Header.Caption = "Value"
    '    e.Layout.Bands(0).Columns("ENT_REMARK").Header.Caption = "Remark"
    '    e.Layout.Bands(0).Columns("BUNNUMB").Header.Caption = "Pjv/Bun.NO"

    '    e.Layout.Bands(0).Columns("ENTDATE").Width = 80
    '    e.Layout.Bands(0).Columns("BRNNAME").Width = 75
    '    e.Layout.Bands(0).Columns("REFYEAR").Width = 60
    '    e.Layout.Bands(0).Columns("TARMODE").Width = 150
    '    e.Layout.Bands(0).Columns("EMPNAME").Width = 250
    '    e.Layout.Bands(0).Columns("SECNAME").Width = 150
    '    e.Layout.Bands(0).Columns("RTTVALUE").Width = 150
    '    e.Layout.Bands(0).Columns("ENT_REMARK").Width = 250
    '    e.Layout.Bands(0).Columns("BUNNUMB").Width = 100

    '    e.Layout.Bands(0).Columns("BRNNAME").FilterOperandStyle = FilterOperandStyle.Combo
    'End Sub

    Private Sub SUMMARY_UltraGrid_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles SUMMARY_UltraGrid.InitializeLayout
        SUMMARY_UltraGrid.DisplayLayout.Override.CellClickAction = CellClickAction.RowSelect
        SUMMARY_UltraGrid.DisplayLayout.Override.SelectTypeRow = SelectType.Single
        SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.FontData.Bold = DefaultableBoolean.True
        SUMMARY_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.ForeColor = Color.Black
        e.Layout.Bands(0).Columns("Tarmode").AllowRowFiltering = DefaultableBoolean.True
        e.Layout.Bands(0).Columns("BRNCODE").Hidden = True
        'e.Layout.Bands(0).Columns("REFYEAR").Hidden = True
        'e.Layout.Bands(0).Columns("REFNUMB").Hidden = True
        e.Layout.Bands(0).Columns("REFSRNO").Hidden = True
        e.Layout.Bands(0).Columns("esename").Hidden = True
        e.Layout.Bands(0).Columns("desname").Hidden = True
        e.Layout.Bands(0).Columns("department").Hidden = True

        e.Layout.Bands(0).Columns("REFYEAR").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("ENTDATE").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("REFNUMB").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("TARMODE").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("BRNNAME").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("EMPNAME").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("SECNAME").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("RTTVALUE").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("ENT_REMARK").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("BUNNUMB").CellAppearance.TextHAlign = HAlign.Center



        e.Layout.Bands(0).Columns("REFYEAR").Header.Caption = "Ac.Year"
        e.Layout.Bands(0).Columns("ENTDATE").Header.Caption = "Ent.Date"
        e.Layout.Bands(0).Columns("REFNUMB").Header.Caption = "Ent.No"
        e.Layout.Bands(0).Columns("TARMODE").Header.Caption = "Mode"
        e.Layout.Bands(0).Columns("BRNNAME").Header.Caption = "Branch"
        e.Layout.Bands(0).Columns("EMPNAME").Header.Caption = "Employee Name"
        e.Layout.Bands(0).Columns("SECNAME").Header.Caption = "Section"
        e.Layout.Bands(0).Columns("RTTVALUE").Header.Caption = "Value"
        e.Layout.Bands(0).Columns("ENT_REMARK").Header.Caption = "Remark"
        e.Layout.Bands(0).Columns("BUNNUMB").Header.Caption = "Pjv/Pac.No"

        e.Layout.Bands(0).Columns("REFYEAR").Width = 60
        e.Layout.Bands(0).Columns("ENTDATE").Width = 80
        e.Layout.Bands(0).Columns("REFNUMB").Width = 60
        e.Layout.Bands(0).Columns("TARMODE").Width = 150
        e.Layout.Bands(0).Columns("BRNNAME").Width = 75
        e.Layout.Bands(0).Columns("EMPNAME").Width = 250
        e.Layout.Bands(0).Columns("SECNAME").Width = 150
        e.Layout.Bands(0).Columns("RTTVALUE").Width = 150
        e.Layout.Bands(0).Columns("ENT_REMARK").Width = 250
        e.Layout.Bands(0).Columns("BUNNUMB").Width = 140
        If MIS = "Y" Then
            e.Layout.Bands(0).Columns("supname").CellAppearance.TextHAlign = HAlign.Left
            e.Layout.Bands(0).Columns("supname").Header.Caption = "Supplier Name"
            e.Layout.Bands(0).Columns("supname").Width = 100

            e.Layout.Bands(0).Columns("ctyname").CellAppearance.TextHAlign = HAlign.Center
            e.Layout.Bands(0).Columns("ctyname").Header.Caption = "City Name"
            e.Layout.Bands(0).Columns("ctyname").Width = 80

        Else

        End If
        e.Layout.Bands(0).Columns("BRNNAME").FilterOperandStyle = FilterOperandStyle.Combo
    End Sub

    Private Sub SUMMARY_UltraGrid_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles SUMMARY_UltraGrid.KeyDown
        If e.KeyCode = Keys.Enter Then
            If dvSUM.Count > 0 Then
                Timer1.Stop()
                APP_DETAIL_Label.Text = "" & vbCrLf & vbCrLf

                drv1 = dvSUM(SUMMARY_UltraGrid.ActiveRow.Index)

                dvAPP = New DataView
                If Mid(drv1("TARMODE"), 1, 1) = "R" Or Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "T" Or Mid(drv1("TARMODE"), 1, 1) = "E" Then
                    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODENAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,INVOICE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.MODECODE")
                ElseIf Mid(drv1("TARMODE"), 1, 1) = "A" Then
                    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,APPRX_PURCHASE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.APTMODE")
                Else
                    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.RESNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,RET_REASON MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.RESCODE")
                End If


                'If Mid(drv1("TARMODE"), 1, 1) = "R" Or Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "T" Or Mid(drv1("TARMODE"), 1, 1) = "E" Then
                '    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,REQ.REFSRNO,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.RESNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,RET_REASON MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.RESCODE ORDER BY REQ.REFSRNO")
                'ElseIf Mid(drv1("TARMODE"), 1, 1) = "A" Then
                '    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODNAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,APPRX_PURCHASE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.APTMODE")
                'ElseIf Mid(drv1("TARMODE"), 1, 1) = "S" Then
                '    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.PUREMPC,DES.DESSRNO,REQ.PUREMPC,EMP.EMPNAME,'SAMPLE' REMARK ,'SAMPLE' REANAME FROM SUPPLIER_SAMPLE_ISSUE REQ ,EMPLOYEE_OFFICE EMP, DESIGNATION DES WHERE REQ.PUREMPC=EMP.EMPSRNO AND  EMP.DESCODE=DES.DESCODE AND REQ.ENTYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.ENTNUMB)=" & drv1("REFNUMB") & " ")
                'Else
                '    dvAPP = TCS_Lib.Get_Cmd_View("SELECT REQ.EMPSRNO,DES.DESSRNO,REQ.TO_EMPSRNO,REQ.EMPNAME,DECODE(TO_NUMBER(REQ.REFSRNO),1,NVL(REQ.ENT_REMARK,'-'),NVL(REQ.APP_REMARK,'-')) REMARK,REQ.RESCODE,MAS.MODENAME REANAME FROM TARGET_REQUEST_APPROVAL REQ,INVOICE_MODE MAS,DESIGNATION DES WHERE REQ.DESCODE=DES.DESCODE AND REQ.REFYEAR='" & drv1("REFYEAR") & "' AND TO_NUMBER(REQ.REFNUMB)=" & drv1("REFNUMB") & " AND REQ.RESCODE=MAS.MODECODE")
                'End If

                APP_DETAIL_Label.Text &= dvAPP(0).Item("REANAME") & vbCrLf & vbCrLf
                For Each drv In dvAPP
                    APP_DETAIL_Label.Text &= drv("EMPNAME") & " - " & drv("REMARK") & vbCrLf & vbCrLf
                Next

                dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
                dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, drv1("REFYEAR"), drv1("REFNUMB"), 1)
                dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
                If dvTRA.Count > 0 Then
                    drvTRA = dvTRA(0)

                    DES_ComboEditor.Enabled = True
                    APPREMA_TextEditor.Enabled = True
                    ENTSTAT_ComboEditor.Enabled = True
                    ENTSTAT_ComboEditor.SelectedIndex = 0
                    Display_Data()
                End If

                DES_ComboEditor.Focus()
            End If
        End If
        If e.KeyCode = Keys.Escape Then
            DES_ComboEditor.Focus()
        End If
    End Sub

    Private Sub Display_Data()

        dv = New DataView
        'dv = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode=" & drvTRA("brncode"))
        'BRANCH_UltraLabel.Text = dv(0).Item("brnname")


        Branch_UltraComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode=" & drvTRA("brncode"))
        Branch_UltraComboEditor.DisplayMember = "brnname"
        Branch_UltraComboEditor.ValueMember = "BRNCODE"

        'TARMODE_UltraComboEditor.Value = IIf(drvTRA("TARMODE") = "R", 1, IIf(drvTRA("TARMODE") = "M", 2, IIf(drvTRA("TARMODE") = "B", 3, 4)))
        'TARMODE_UltraComboEditor.Value = IIf(drvTRA("TARMODE") = "R", 1, IIf(drvTRA("TARMODE") = "M", 2, IIf(drvTRA("TARMODE") = "B", 3, IIf(drvTRA("TARMODE") = "T", 5, 4))))
        TARMODE_UltraComboEditor.Value = IIf(drvTRA("TARMODE") = "R", 1, IIf(drvTRA("TARMODE") = "M", 2, IIf(drvTRA("TARMODE") = "B", 3, IIf(drvTRA("TARMODE") = "T", 5, IIf(drvTRA("TARMODE") = "T", 6, 4)))))
        If IsDBNull(drvTRA("SUPCODE")) = False Then
            SUPCODE_UltraTextEditor.ReadOnly = True
            SUPCODE_UltraTextEditor.Text = drvTRA("SUPCODE")
            SUPCODE_UltraTextEditor.Tag = drvTRA("SUPCODE")
        End If

        If IsDBNull(drvTRA("BUNYEAR")) = False Then
            BUNYEAR_TextBox.Text = drvTRA("BUNYEAR")
            BUNNUMB_TextBox.Text = drvTRA("BUNNUMB")
        End If

        If IsDBNull(drvTRA("TO_BRNCODE")) = False Then
            TOBRNCODE_ComboBox.DataSource = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode = " & drvTRA("TO_BRNCODE") & " order by brncode")
            TOBRNCODE_ComboBox.DisplayMember = "brnname"
            TOBRNCODE_ComboBox.ValueMember = "brncode"
            TOBRNCODE_ComboBox.SelectedIndex = 0

            TOBRNCODE_ComboBox.SelectedValue = drvTRA("TO_BRNCODE")
        End If

        If drvTRA("TARMODE") = "R" Or drvTRA("TARMODE") = "M" Or drvTRA("TARMODE") = "T" Or drvTRA("TARMODE") = "E" Then
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select rescode,resname from ret_reason where RESCODE not in (29) order by rescode")
        ElseIf drvTRA("TARMODE") = "A" Then
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select aptmode rescode,modname resname from apprx_purchase_mode order by aptmode")
        Else
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select modecode rescode,modename resname from invoice_mode order by modecode")
        End If
        RESCODE_ComboEditor.DisplayMember = "resname"
        RESCODE_ComboEditor.ValueMember = "rescode"
        RESCODE_ComboEditor.DataBind()

        RESCODE_ComboEditor.Value = drvTRA("RESCODE")

        SECCODE_ComboEditor.Value = drvTRA("SECCODE")
        RTTVALU_TextBox.Text = drvTRA("RTTVALUE")
        RTTQNTY_TextBox.Text = IIf(IsDBNull(drvTRA("RTTQNTY")) = True, 0, drvTRA("RTTQNTY"))

        REQSRNO_TextEditor.Text = drvTRA("EMPCODE") & "-" & drvTRA("EMPNAME")
        REQSRNO_TextEditor.Tag = drvTRA("EMPSRNO")

        dv = New DataView
        dv = TCS_Lib.Get_Cmd_View("select ese.esename,des.desname,des.dessrno from empsection ese,designation des where ese.esecode=" & drvTRA("ESECODE") & " and des.descode=" & drvTRA("DESCODE"))
        REQDETL_UltraLabel.Text = "SECTION : " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "DESIGNATION : " & dv(0).Item("DESNAME")
        REQDETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

        If IsDBNull(drvTRA("TO_EMPSRNO")) = False Then
            TOEMPSRNO_TextEditor.Text = drvTRA("TO_EMPCODE") & "-" & drvTRA("TO_EMPNAME")
            TOEMPSRNO_TextEditor.Tag = drvTRA("TO_EMPSRNO")

            dv = New DataView
            dv = TCS_Lib.Get_Cmd_View("select ese.esename,des.desname from empsection ese,designation des where ese.esecode=" & drvTRA("TO_ESECODE") & " and des.descode=" & drvTRA("TO_DESCODE"))
            TODETL_UltraLabel.Text = "SECTION : " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "DESIGNATION : " & dv(0).Item("DESNAME")
        End If

        REMA_TextEditor.Text = IIf(IsDBNull(drvTRA("ENT_REMARK")) = True, "-", drvTRA("ENT_REMARK"))
    End Sub

    Private Sub Target_Request_Approval_Form_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles Me.KeyDown
        If (TARMODE_UltraComboEditor.SelectedIndex = 1 Or TARMODE_UltraComboEditor.SelectedIndex = 6) And Not Me.Detail_UltraGrid.ActiveRow Is Nothing Then
            If e.KeyCode = Keys.F7 Then
                Mnual_GroupBox.Enabled = True
                Mnual_GroupBox.Visible = True
                Itmcode_TextEditor.Enabled = True
                Itmcode_TextEditor.Focus()
            End If
        End If
    End Sub

    Private Sub ENTMODE_UltraComboEditor_ValueChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles TARMODE_UltraComboEditor.ValueChanged
        TRNSUP_UltraLabel.Visible = False

        If TARMODE_UltraComboEditor.Value = 1 Then
            UltraLabel4.Text = "Packing Slip No."
            BUNYEAR_TextBox.Enabled = True
            BUNNUMB_TextBox.Enabled = True
            BUNYEAR_TextBox.Text = TCS_Lib.Get_Acyear
            TOBRNCODE_ComboBox.Enabled = False
            SUPCODE_UltraTextEditor.Enabled = False
            TOBRNCODE_ComboBox.DataSource = Nothing
            RTTQNTY_TextBox.Enabled = False
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select rescode,resname from ret_reason where RESCODE not in (29,30) order by rescode")
            WOS_CheckBox.Enabled = False
            itm_save = False
            BUNYEAR_TextBox.Focus()
            RTTVALU_TextBox.ReadOnly = False

            'If TARMODE_UltraComboEditor.Value = 5 Then
            '    SUPCODE_UltraTextEditor.Enabled = True
            '    RTTQNTY_TextBox.Enabled = True
            '    SUPCODE_UltraTextEditor.Focus()
            'End If

        ElseIf TARMODE_UltraComboEditor.Value = 2 Or TARMODE_UltraComboEditor.Value = 6 Then
            UltraLabel4.Text = "PJV No."
            BUNYEAR_TextBox.Enabled = False
            BUNNUMB_TextBox.Enabled = False
            TOBRNCODE_ComboBox.Enabled = False
            SUPCODE_UltraTextEditor.ReadOnly = False
            SUPCODE_UltraTextEditor.Enabled = True
            TOBRNCODE_ComboBox.DataSource = Nothing
            RTTQNTY_TextBox.Enabled = False

            WOS_CheckBox.Enabled = False
            If TARMODE_UltraComboEditor.Value = 6 Then
                WOS_CheckBox.Enabled = True
            End If
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select rescode,resname from ret_reason where RESCODE not in (29,30) order by rescode")
            SUPCODE_UltraTextEditor.Focus()
            itm_save = True

        ElseIf TARMODE_UltraComboEditor.Value = 3 Then
            BUNYEAR_TextBox.Enabled = False
            BUNNUMB_TextBox.Enabled = False
            TOBRNCODE_ComboBox.Enabled = True
            SUPCODE_UltraTextEditor.Enabled = False
            BUNYEAR_TextBox.Clear()
            BUNNUMB_TextBox.Clear()
            SUPCODE_UltraTextEditor.Clear()
            SUPCODE_UltraTextEditor.Tag = 0
            RTTQNTY_TextBox.Enabled = False
            WOS_CheckBox.Enabled = False

            'TOBRNCODE_ComboBox.DataSource = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode <> " & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)) & " order by brncode")
            TOBRNCODE_ComboBox.DataSource = TCS_Lib.Get_Cmd_View("select brncode,brnname from branch where deleted='N' and brncode Not In(100,103,109,110,111,114) And Brnmode='B' order by brncode")
            TOBRNCODE_ComboBox.DisplayMember = "brnname"
            TOBRNCODE_ComboBox.ValueMember = "brncode"
            TOBRNCODE_ComboBox.SelectedIndex = 0
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select modecode rescode,modename resname from invoice_mode order by modecode")
            itm_save = False
            RTTVALU_TextBox.ReadOnly = False

        ElseIf TARMODE_UltraComboEditor.Value = 4 Then
            BUNYEAR_TextBox.Enabled = False
            BUNNUMB_TextBox.Enabled = False
            TOBRNCODE_ComboBox.Enabled = False
            SUPCODE_UltraTextEditor.Enabled = False
            BUNYEAR_TextBox.Clear()
            BUNNUMB_TextBox.Clear()
            SUPCODE_UltraTextEditor.Clear()
            SUPCODE_UltraTextEditor.Tag = 0
            WOS_CheckBox.Enabled = False
            RTTQNTY_TextBox.Enabled = True
            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select aptmode rescode,modname resname from apprx_purchase_mode order by aptmode")
            itm_save = False
            RTTVALU_TextBox.ReadOnly = False
        ElseIf TARMODE_UltraComboEditor.Value = 5 Then
            UltraLabel4.Text = "Packing Slip No."
            BUNYEAR_TextBox.Enabled = True
            BUNNUMB_TextBox.Enabled = True
            BUNYEAR_TextBox.Text = TCS_Lib.Get_Acyear
            TOBRNCODE_ComboBox.Enabled = False
            TOBRNCODE_ComboBox.DataSource = Nothing

            RESCODE_ComboEditor.DataSource = TCS_Lib.Get_Cmd_View("select rescode,resname from ret_reason where RESCODE=30 order by rescode")
            WOS_CheckBox.Enabled = False
            itm_save = True
            RTTVALU_TextBox.ReadOnly = False
            RTTQNTY_TextBox.Enabled = False
            SUPCODE_UltraTextEditor.ReadOnly = False
            SUPCODE_UltraTextEditor.Enabled = True
            SUPCODE_UltraTextEditor.Focus()

        End If

        RESCODE_ComboEditor.Enabled = True
        RESCODE_ComboEditor.DisplayMember = "resname"
        RESCODE_ComboEditor.ValueMember = "rescode"
        RESCODE_ComboEditor.DataBind()
    End Sub

    Private Sub RESCODE_ComboEditor_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles RESCODE_ComboEditor.InitializeLayout
        Try
            e.Layout.Override.SelectedRowAppearance.ForeColor = Color.Black
            e.Layout.Override.SelectedRowAppearance.FontData.Bold = Infragistics.Win.DefaultableBoolean.True
            e.Layout.Override.RowAppearance.TextVAlign = Infragistics.Win.VAlign.Middle
            e.Layout.Override.SelectedRowAppearance.TextVAlign = Infragistics.Win.VAlign.Middle

            e.Layout.Bands(0).Columns("RESCODE").Hidden = True

            e.Layout.Bands(0).Columns("RESNAME").Header.Caption = "Reason"
            e.Layout.Bands(0).Columns("RESNAME").Width = 400
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Warning)
        End Try
    End Sub

    Private Sub SUPCODE_UltraTextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles SUPCODE_UltraTextEditor.KeyDown
        Try
            TRNSUP_UltraLabel.Visible = False
            If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
                DVSUP = New DataView
                If TARMODE_UltraComboEditor.Value <> 5 Or TARMODE_UltraComboEditor.Value = 6 Then
                    If Val(SUPCODE_UltraTextEditor.Text) >= 7000 Then
                        SUPCODE_UltraTextEditor.Tag = Val(SUPCODE_UltraTextEditor.Text)
                        If Val(SUPCODE_UltraTextEditor.Tag) = 7000 Or Val(SUPCODE_UltraTextEditor.Tag) = 23237 Or Val(SUPCODE_UltraTextEditor.Tag) = 23238 Or Val(SUPCODE_UltraTextEditor.Tag) = 26216 Then
                            MessageBox.Show("Can't Make Debit Request Entry for this Supplier!", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            SUPCODE_UltraTextEditor.Text = ""
                            Exit Sub
                        End If

                        If TARMODE_UltraComboEditor.Value = 6 Then
                            Invoice_Load()
                            ''ITMCODE_ENTER()
                        Else
                            Invoice_Load()
                        End If
                        'Invoice_Load()
                    Else
                        MessageBox.Show("Enter Valid Supplier Code", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Exit Sub
                    End If
                ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                    'BUNYEAR_TextBox.Enabled = True
                    'BUNNUMB_TextBox.Enabled = True
                    'RESCODE_ComboEditor.Enabled = True

                    SUPCODE_UltraTextEditor.Tag = Val(SUPCODE_UltraTextEditor.Text)
                    DVSUP = TCS_Lib.Get_Cmd_View("select sup.SUPCODE,sup.SUPNAME,sup.SUPNAME||' - '||cty.ctyname name from supplier_asset sup,city cty where sup.supcode=" & SUPCODE_UltraTextEditor.Tag & " and cty.ctycode=sup.ctycode and sup.supcode in (select supcode from transport where supcode>0) order by sup.SUPCODE")
                    If DVSUP.Count = 0 Then
                        MessageBox.Show("Transport Supplier Not Vaild", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        SUPCODE_UltraTextEditor.Text = ""
                        SUPCODE_UltraTextEditor.Tag = 0
                        Exit Sub
                    Else
                        TRNSUP_UltraLabel.Visible = True
                        SUPCODE_UltraTextEditor.Tag = DVSUP(0).Item("SUPCODE")
                        TRNSUP_UltraLabel.Text = DVSUP(0).Item("SUPNAME")
                    End If
                End If

            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra - Error", MessageBoxButtons.OK, MessageBoxIcon.Exclamation)
        End Try
    End Sub

    Private Sub SUPCODE_UltraTextEditor_TextChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles SUPCODE_UltraTextEditor.TextChanged
        Try
            If SUPCODE_UltraTextEditor.Tag > 0 Then
                SUPCODE_UltraTextEditor.Clear()
                SUPCODE_UltraTextEditor.Tag = 0
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub Invoice_Load()
        dvPURINVN = New DataView

        'dvPURINVN = TCS_Lib.Get_Cmd_View("select distinct(det.purinvn) purinvn,det.purdate purdate,DET.PURYEAR PURYEAR,DET.PURCONO,(AMT.BILLAMT+AMT.TAXAMNT) puramnt " & _
        '                    " from pur_det det,pur_con con,branch_company bcom,section_group_payment grp,PUR_DET_AMT AMT  " & _
        '                    " where det.deleted='N' and det.purpaid='N' and det.bunrecv='Y' and det.brncode=con.brncode and det.puryear=con.puryear and det.purcono=con.purcono  " & _
        '                    " and nvl(det.posticker,'N')=nvl(DET.POBILLCHK ,'N')  and det.PURGRAD<>'XXX' and det.brncode=bcom.brncode and det.seccode=grp.seccode  " & _
        '                    " AND DET.PURYEAR=AMT.PURYEAR AND DET.PURCONO=AMT.PURCONO AND DET.BRNCODE=AMT.BRNCODE and det.supcode=" & SUPCODE_UltraTextEditor.Tag & "" & _
        '                    " group by det.purinvn,DET.BRNCODE,DET.PURYEAR,DET.PURCONO,det.supcode,det.purdate,det.puramnt,det.ADTREMA,con.purrdis,con.PURSDIS,det.ADTUSER,bcom.comcode,grp.SECGRNO,AMT.BILLAMT,AMT.TAXAMNT order by puramnt desc")

        'dvPURINVN = TCS_Lib.Get_Cmd_View("select distinct(det.purinvn) purinvn,det.purdate purdate,DET.PURYEAR PURYEAR,DET.PURCONO,(AMT.BILLAMT+AMT.TAXAMNT) puramnt " & _
        '                   " from pur_det det,pur_con con,branch_company bcom,section_group_payment grp,PUR_DET_AMT AMT,PUR_DET_LR LR  " & _
        '                   " where det.deleted='N' and det.purpaid='N' and (det.bunrecv='Y' or lr.trncode is not null) and det.brncode=con.brncode and det.puryear=con.puryear and det.purcono=con.purcono  " & _
        '                   " and nvl(det.posticker,'N')=nvl(DET.POBILLCHK ,'N')  and det.PURGRAD<>'XXX' and det.brncode=bcom.brncode and det.seccode=grp.seccode  " & _
        '                   " AND DET.PURYEAR=AMT.PURYEAR AND DET.PURCONO=AMT.PURCONO AND DET.BRNCODE=AMT.BRNCODE AND lr.puryear=det.puryear and lr.purcono=det.purcono and det.supcode=" & SUPCODE_UltraTextEditor.Tag & "" & _
        '                   " group by det.purinvn,DET.BRNCODE,DET.PURYEAR,DET.PURCONO,det.supcode,det.purdate,det.puramnt,det.ADTREMA,con.purrdis,con.PURSDIS,det.ADTUSER,bcom.comcode,grp.SECGRNO,AMT.BILLAMT,AMT.TAXAMNT order by puramnt desc")
        If SUPCODE_UltraTextEditor.Tag > 0 Or (TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2) Then
            If Super_bazar_CheckBox.Checked = True Then
                dvPURINVN = TCS_Lib.Get_Cmd_View("select distinct(det.purinvn) purinvn,det.purdate purdate,DET.PURYEAR PURYEAR,DET.PURCONO,nvl(((AMT.BILLAMT+AMT.TAXAMNT) -Nvl((Select Sum(RTTVALU) from return_target where bunyear(+)=det.puryear and bunnumb(+)=det.purcono and deleted='N' and retmode='M'),0)),0) puramnt " & _
                                          " from sb_pur_det det,sb_pur_con con,branch_company bcom,section_group_payment grp,sb_PUR_DET_AMT AMT,sb_pur_det_lr lr,supplier sup  " & _
                                          " where det.deleted='N' and det.purpaid='N' and (det.bunrecv='Y' or lr.trncode is not null) and det.brncode=con.brncode and det.puryear=con.puryear and det.purcono=con.purcono  " & _
                                          " and nvl(det.posticker,'N')=nvl(DET.POBILLCHK ,'N')  and det.PURGRAD<>'XXX' and det.brncode=bcom.brncode and det.seccode=grp.seccode  " & _
                                          " AND DET.PURYEAR=AMT.PURYEAR AND DET.PURCONO=AMT.PURCONO AND DET.BRNCODE=AMT.BRNCODE and lr.puryear=det.puryear and lr.purcono=det.purcono and nvl(((AMT.BILLAMT+AMT.TAXAMNT) -Nvl((Select Sum(RTTVALU) from return_target where bunyear(+)=det.puryear and bunnumb(+)=det.purcono and deleted='N' and retmode='M'),0)),0)>0 and det.supcode=" & SUPCODE_UltraTextEditor.Tag & "" & _
                                          " and sup.supcode=det.supcode and sup.sup_active='Y' " & _
                                          " group by det.purinvn,det.purdate,DET.PURYEAR,DET.PURCONO,AMT.BILLAMT,AMT.TAXAMNT order by puramnt desc")
            Else
                dvPURINVN = TCS_Lib.Get_Cmd_View("select distinct(det.purinvn) purinvn,det.purdate purdate,DET.PURYEAR PURYEAR,DET.PURCONO,nvl(((AMT.BILLAMT+AMT.TAXAMNT) -Nvl((Select Sum(RTTVALU) from return_target where bunyear(+)=det.puryear and bunnumb(+)=det.purcono and deleted='N' and retmode='M'),0)),0) puramnt " & _
                                          " from pur_det det,pur_con con,branch_company bcom,section_group_payment grp,PUR_DET_AMT AMT,pur_det_lr lr,supplier sup  " & _
                                          " where det.deleted='N' and det.purpaid='N' and (det.bunrecv='Y' or lr.trncode is not null) and det.brncode=con.brncode and det.puryear=con.puryear and det.purcono=con.purcono  " & _
                                          " and nvl(det.posticker,'N')=nvl(DET.POBILLCHK ,'N')  and det.PURGRAD<>'XXX' and det.brncode=bcom.brncode and det.seccode=grp.seccode  " & _
                                          " AND DET.PURYEAR=AMT.PURYEAR AND DET.PURCONO=AMT.PURCONO AND DET.BRNCODE=AMT.BRNCODE and lr.puryear=det.puryear and lr.purcono=det.purcono and nvl(((AMT.BILLAMT+AMT.TAXAMNT) -Nvl((Select Sum(RTTVALU) from return_target where bunyear(+)=det.puryear and bunnumb(+)=det.purcono and deleted='N' and retmode='M'),0)),0)>0 and det.supcode=" & SUPCODE_UltraTextEditor.Tag & "" & _
                                          " and sup.supcode=det.supcode and sup.sup_active='Y' " & _
                                          " group by det.purinvn,det.purdate,DET.PURYEAR,DET.PURCONO,AMT.BILLAMT,AMT.TAXAMNT order by puramnt desc")
            End If
           

            If dvPURINVN.Count > 0 Then

                Dim Clm As DataColumn
                Clm = New DataColumn("ACCSTAT", GetType(Char))
                dvPURINVN.Table.Columns.Add(Clm)

                Dim drv As DataRowView
                For Each drv In dvPURINVN
                   
                        dv = New DataView
                        dv = TCS_Lib.Get_Cmd_View("SELECT COUNT(*) Cnt FROM PUR_DET_PAYMENT WHERE ACCSTAT='Y' AND PURYEAR='" & drv("PURYEAR") & "' AND PURCONO=" & drv("PURCONO"))
                   

                    If dv(0).Item("CNT") > 0 Then
                        drv.BeginEdit()
                        drv("ACCSTAT") = "Y"
                        drv.EndEdit()
                    Else
                        drv.BeginEdit()
                        drv("ACCSTAT") = "N"
                        drv.EndEdit()
                    End If
                Next

                With dvPURINVN
                    .AllowNew = False
                    .AllowEdit = False
                    .AllowDelete = False
                End With
                Try

                    dsRET = New DataSet
                    dsRET = CData.Get_Tex_Return_Purinvn(dsRET, Val(SUPCODE_UltraTextEditor.Tag))
                    ManRet_Tar = drv("PURAMNT") - Return_Amount(drv("PURYEAR"), drv("PURCONO"))

                    SendKeys.Flush()
                    Dim PCnt As Integer = dvPURINVN.Table.Compute("count(ACCSTAT)", "ACCSTAT='Y'")
                    dvPURINVN.RowFilter = "ACCSTAT='N'"
                    If dvPURINVN.Count = 0 Then
                        If PCnt > 0 Then
                            MessageBox.Show(PCnt & " Invoice(s) Pending with Account Pass. Contact Accounts Dept.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Else
                            'MessageBox.Show("There is No Outstanding Bill Pending for this Supplier", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            If TARMODE_UltraComboEditor.Value = 6 Then
                            Else
                                MessageBox.Show("There is No Outstanding Bill Pending for this Supplier", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                Exit Sub
                            End If
                        End If
                        Exit Sub
                    End If
                    'WOS_CheckBox.Enabled = False
                    'WOS_CheckBox.Checked = False
                    'WOS_CheckBox.Visible = False
                    'WOS_CheckBox.Checked = False
                    Invoice_Form_Display()

                    If TARMODE_UltraComboEditor.SelectedIndex = 1 Or TARMODE_UltraComboEditor.SelectedIndex = 5 Then
                        ITMCODE_ENTER()
                        TARMODE_UltraComboEditor.ReadOnly = True
                    End If
                Catch ex As Exception
                    MessageBox.Show(ex.Message, "TCS Centra - Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
                End Try
            Else
                ''MessageBox.Show("There is No Outstanding Bill Pending for this Supplier", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                If TARMODE_UltraComboEditor.Value = 6 Then
                Else
                    MessageBox.Show("There is No Outstanding Bill Pending for this Supplier", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    Exit Sub
                End If
            End If
        End If
    End Sub

    Dim ManRet_Tar As Decimal = 0

    Private Sub Invoice_Form_Display()
        Dim aRow As DataRow
        Dim Cmdstr As String = " "

        Dim Inv As New Pay_Inv_Form(dvPURINVN, Cmdstr)
        Inv.TopMost = True
        Inv.ShowDialog(Me)
        If Inv.PURINVN_ = " " Then
            SUPCODE_UltraTextEditor.Focus()
        Else

            Dim dvAMT As New DataView
            Dim drvAMT As DataRowView
            If Super_bazar_CheckBox.Checked = True Then
                dvAMT = TCS_Lib.Get_Cmd_View("select BILLAMT,DISCAMT,TAXAMNT,NETAMNT from sb_pur_DET_AMT where puryear='" & Inv.PURYEAR_ & "' and purcono=" & Inv.PURCONO_)
                drvAMT = dvAMT(0)
            Else
                dvAMT = TCS_Lib.Get_Cmd_View("select BILLAMT,DISCAMT,TAXAMNT,NETAMNT from pur_DET_AMT where puryear='" & Inv.PURYEAR_ & "' and purcono=" & Inv.PURCONO_)
                drvAMT = dvAMT(0)
            End If
           

            BUNYEAR_TextBox.Text = Inv.PURYEAR_
            BUNNUMB_TextBox.Text = Inv.PURCONO_

            'ManRet_Tar = drvAMT("BILLAMT") + drvAMT("TAXAMNT") - drvAMT("DISCAMT") - Return_Amount(Inv.PURYEAR_, Inv.PURCONO_)
            ManRet_Tar = drvAMT("NETAMNT") - Return_Amount(Inv.PURYEAR_, Inv.PURCONO_)
            RTTVALU_TextBox.Focus()
        End If
    End Sub

    Private Sub ITMCODE_ENTER()
        Try

            Me.REQUEST_TabControl.Tabs(1).Enabled = False
            Me.REQUEST_TabControl.Tabs(2).Enabled = False

            Mnual_GroupBox.Visible = True
            Mnual_GroupBox.Enabled = True
            Itmcode_TextEditor.Enabled = True

            Mnual_GroupBox.BringToFront()

            Mtr_TextBox.Enabled = False
            mtr_Label.Enabled = False
            mtr_Label.Visible = False
            Mtr_TextBox.Visible = False
            QTY_Label.Text = ""

            Itmcode_TextEditor.Text = ""
            Detail_UltraGrid.DataSource = Nothing
            TVAl_Label.Text = ""
            Itmcode_TextEditor.Focus()

            ''dvitm = TCS_Lib.Get_Cmd_View("SELECT  0 ENTSRNO,'' ITMCODE, 0 ITMRATE,0 qty, 0 val FROM DUAL WHERE 1=2")
            dvitm = TCS_Lib.Get_Cmd_View("SELECT  0 ENTSRNO,'' ITMCODE, 0 ITMRATE,0 qty, 0 val,(Select Empphot From Employee_Personal Where Empsrno=939) SUPPHTO FROM DUAL WHERE 1=2")

            Detail_UltraGrid.DataSource = dvitm


        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra - Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Function Return_Amount(ByVal iPURYEAR As String, ByVal iPURCONO As Decimal) As Decimal
        Try
            Dim Amt As Decimal = 0
            If dsRET.Tables.Count > 0 Then
                Amt = IIf(IsDBNull(dsRET.Tables(0).Compute("sum(retnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO)) = True, 0, dsRET.Tables(0).Compute("sum(retnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO))
                Amt += IIf(IsDBNull(dsRET.Tables(1).Compute("sum(retnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO)) = True, 0, dsRET.Tables(1).Compute("sum(retnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO))
                Amt += IIf(IsDBNull(dsRET.Tables(2).Compute("sum(crsnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO)) = True, 0, dsRET.Tables(2).Compute("sum(crsnett)", "puryear='" & iPURYEAR & "' and purcono=" & iPURCONO))
            End If
            Return Amt

        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    'Private Sub REQSRNO_TextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles REQSRNO_TextEditor.KeyDown
    '    Try
    '        If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
    '            dv = New DataView
    '            dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empcode=" & Val(REQSRNO_TextEditor.Text))
    '            If dv.Count > 0 Then

    '                REQSRNO_TextEditor.Text = dv(0).Item("EMPCODE") & "-" & dv(0).Item("EMPNAME")
    '                REQSRNO_TextEditor.Tag = dv(0).Item("EMPSRNO")

    '                REQDETL_UltraLabel.Text = "Branch: " & dv(0).Item("BRNNAME") & vbCrLf & vbCrLf & "Section: " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "Designation: " & dv(0).Item("DESNAME")
    '                REQDETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

    '                bcode = dv(0).Item("BRNCODE")
    '                ecode = dv(0).Item("ESECODE")
    '                dcode = dv(0).Item("DESCODE")
    '            Else
    '                MessageBox.Show("Enter Valid Employee Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                REQSRNO_TextEditor.Clear()
    '                REQSRNO_TextEditor.Focus()
    '                Exit Sub
    '            End If
    '        End If
    '    Catch ex As Exception
    '        MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    End Try
    'End Sub

    Private Sub REQSRNO_TextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles REQSRNO_TextEditor.KeyDown
        Try
            If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
                dv = New DataView
                dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and brn.brncode=" & IIf(Branch_UltraComboEditor.Value = 21, 16, Branch_UltraComboEditor.Value) & " and emp.empcode=" & Val(REQSRNO_TextEditor.Text))
                If dv.Count > 0 Then

                    REQSRNO_TextEditor.Text = dv(0).Item("EMPCODE") & "-" & dv(0).Item("EMPNAME")
                    REQSRNO_TextEditor.Tag = dv(0).Item("EMPSRNO")

                    REQDETL_UltraLabel.Text = "Branch: " & dv(0).Item("BRNNAME") & vbCrLf & vbCrLf & "Section: " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "Designation: " & dv(0).Item("DESNAME")
                    REQDETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

                    bcode = dv(0).Item("BRNCODE")
                    ecode = dv(0).Item("ESECODE")
                    dcode = dv(0).Item("DESCODE")
                Else
                    MessageBox.Show("Enter Valid Employee Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    REQSRNO_TextEditor.Clear()
                    REQSRNO_TextEditor.Focus()
                    REQDETL_UltraLabel.Text = ""
                    REQDETL_UltraLabel.Tag = 0
                    Exit Sub
                End If
            End If
            If e.KeyCode = Keys.Back Or e.KeyCode = Keys.Delete Then
                REQSRNO_TextEditor.Clear()
                REQSRNO_TextEditor.Focus()
                REQDETL_UltraLabel.Text = ""
                REQDETL_UltraLabel.Tag = 0
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub REQSRNO_TextEditor_TextChanged(ByVal sender As Object, ByVal e As System.EventArgs) Handles REQSRNO_TextEditor.TextChanged
        Try
            If REQSRNO_TextEditor.Tag > 0 Then
                REQSRNO_TextEditor.Tag = 0
                REQSRNO_TextEditor.Clear()

                bcode = 0
                ecode = 0
                dcode = 0
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Public Function Target_Request_Approval_Form_Save() As Boolean
        Try
            If LMode = "NEW" Then
                If TARMODE_UltraComboEditor.Value = 1 Then
                    If BUNYEAR_TextBox.Text.Trim.Length <> 7 Then
                        MessageBox.Show("Enter Valid A/c Year", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        BUNYEAR_TextBox.Focus()
                        Exit Function
                    End If

                    If Val(BUNNUMB_TextBox.Text) = 0 Then
                        MessageBox.Show("Enter Valid Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        BUNNUMB_TextBox.Focus()
                        Exit Function
                    End If

                    dv = New DataView
                    dv = TCS_Lib.Get_Cmd_View("select bunopen from packing_slip where pacyear='" & BUNYEAR_TextBox.Text.Trim & "' and pacnumb=" & Val(BUNNUMB_TextBox.Text) & " and deleted='N'")
                    If dv.Count > 0 Then
                        If dv(0).Item("BUNOPEN") = "N" Then
                            MessageBox.Show("Entered Bundle Number is not Opened", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            Exit Function
                        End If

                        dv = New DataView
                        dv = TCS_Lib.Get_Cmd_View("select accstat from pur_det_payment det,bundle_summary bsum where det.puryear=bsum.puryear and det.purcono=bsum.purcono and bsum.bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bsum.bunnumb=" & Val(BUNNUMB_TextBox.Text) & " and bsum.deleted='N'")
                        If dv.Count > 0 Then
                            If dv(0).Item("ACCSTAT") = "Y" Then
                                MessageBox.Show("Already Account Payment Pass Entry Made for this entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                Exit Function
                            End If
                        End If

                        dv = New DataView
                        dv = TCS_Lib.Get_Cmd_View("select purpaid from pur_det det,bundle_summary bsum where det.puryear=bsum.puryear and det.purcono=bsum.purcono and bsum.bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bsum.bunnumb=" & Val(BUNNUMB_TextBox.Text) & " and bsum.deleted='N' and det.deleted='N'")
                        If dv.Count > 0 Then
                            If dv(0).Item("PURPAID") = "Y" Then
                                MessageBox.Show("Already Payment Paid for this entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                Exit Function
                            End If
                        End If

                    Else
                        MessageBox.Show("Enter Valid Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Exit Function
                    End If


                    '' Update Date Lock

                    Dim DvUpd_DateChk As DataView
                    DvUpd_DateChk = New DataView
                    DvUpd_DateChk = TCS_Lib.Get_Cmd_View("select brncode,bunyear,bunnumb,trunc(sysdate)-trunc(adddate) elg_date from stock_bundle_summary where brncode=" & Branch_UltraComboEditor.Value & " and bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bunnumb=" & Val(BUNNUMB_TextBox.Text))

                    If DvUpd_DateChk.Count = 0 Then
                        MessageBox.Show("Verify Entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Exit Function
                    Else
                        If DvUpd_DateChk(0).Item("elg_date") > 1 Then
                            MessageBox.Show("Debit Request Can't Be Made For This Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            Exit Function
                        End If
                    End If

                ElseIf TARMODE_UltraComboEditor.Value = 2 Then
                    If WOS_CheckBox.Checked = False Then
                        If BUNYEAR_TextBox.Text.Trim.Length <> 7 Then
                            MessageBox.Show("Enter Valid A/c Year", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            BUNYEAR_TextBox.Focus()
                            Exit Function
                        End If

                        If Val(BUNNUMB_TextBox.Text) = 0 Then
                            MessageBox.Show("Enter Valid PJV Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            BUNNUMB_TextBox.Focus()
                            Exit Function
                        End If

                        If TARMODE_UltraComboEditor.Value <> 5 Then
                            If Val(RTTVALU_TextBox.Text) > ManRet_Tar Then
                                MessageBox.Show("Target Amount Exceed than Selected Invoice Amount", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                RTTVALU_TextBox.Clear()
                                RTTVALU_TextBox.Focus()
                                Exit Function
                            End If
                        End If
                    End If

                    Dim dvPJV As New DataView
                    dvPJV = TCS_Lib.Get_Cmd_View(" Select Count(*) Cnt From  Trandata.Manual_Return_Slip_Summary@tcscentr Where deleted='N' and   Puryear='" & BUNYEAR_TextBox.Text & "' And Purcono=" & Val(BUNNUMB_TextBox.Text) & " And (Brncode,Mrtyear,Mrtnumb) Not in " & _
                                                 " (Select Brncode,Mrtyear,Mrtnumb From  Trandata.Manual_Return_Summary@tcscentr  Where Puryear='" & BUNYEAR_TextBox.Text & "' And Purcono=" & Val(BUNNUMB_TextBox.Text) & ")")
                    If dvPJV(0)("Cnt") > 0 Then
                        MessageBox.Show("For this PJV Already a Manual Return Slip Was Pending...! Enter Valid PJV Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        BUNNUMB_TextBox.Focus()
                        Exit Function
                    End If
                    If SUPCODE_UltraTextEditor.Tag = 0 Then
                        MessageBox.Show("Enter Valid Supplier Code", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        SUPCODE_UltraTextEditor.Clear()
                        SUPCODE_UltraTextEditor.Focus()
                        Exit Function
                    End If
                ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                    If SUPCODE_UltraTextEditor.Tag = 0 Then
                        MessageBox.Show("Enter Valid Transport Supplier Code", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        SUPCODE_UltraTextEditor.Clear()
                        SUPCODE_UltraTextEditor.Focus()
                        Exit Function
                    End If

                End If

                If Val(RTTVALU_TextBox.Text) = 0 Then
                    MessageBox.Show("Enter Valid Target Value", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    RTTVALU_TextBox.Focus()
                    Exit Function
                End If

                If REQSRNO_TextEditor.Tag = 0 Then
                    MessageBox.Show("Enter Valid Request By Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    REQSRNO_TextEditor.Focus()
                    Exit Function
                End If

                If TOEMPSRNO_TextEditor.Tag = 0 Then
                    MessageBox.Show("Enter Valid Request To Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    TOEMPSRNO_TextEditor.Focus()
                    Exit Function
                End If
                If RESCODE_ComboEditor.Value = Nothing Then
                    MessageBox.Show("Select Valid Reason", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
                    RESCODE_ComboEditor.Focus()
                    Exit Function
                End If

                'If TARMODE_UltraComboEditor.Value = 5 Then
                '    MessageBox.Show("Not Eligible for this Option", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                '    Exit Function
                'End If

                If TARMODE_UltraComboEditor.Value = 5 And RESCODE_ComboEditor.Value <> 30 Then
                    MessageBox.Show("Selected Reason Not Entered For Transport Debit Mode", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    Exit Function
                End If

            End If

           
            If MessageBox.Show("Do you want to Save", "TCS Centra - Save?", MessageBoxButtons.YesNo, MessageBoxIcon.Question) = Windows.Forms.DialogResult.Yes Then

                If LMode = "FIND" Then

                    If Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "P" And (Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "R") Then
                        If DES_ComboEditor.Value = 5 Then
                            MessageBox.Show("You are not authorised for Target Request Mis Dept Change To GM Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            ENTSTAT_ComboEditor.Focus()
                            Exit Function
                        End If
                    End If

                    If Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "O" Then
                        If Mid(drv1("TARMODE"), 1, 1) <> "S" Then

                            If Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "R" Then
                                If GM = "Y" Then
                                    Convert = "Y"
                                Else
                                    ' If DES_ComboEditor.Value = 5 Then
                                    MessageBox.Show("You are not authorised for Target Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                    ENTSTAT_ComboEditor.Focus()
                                    Exit Function
                                    'End If
                                End If

                            End If
                            If Mid(drv1("TARMODE"), 1, 1) <> "M" And Mid(drv1("TARMODE"), 1, 1) <> "R" Then
                                If MIS = "Y" Then
                                    Convert = "Y"
                                Else
                                    MessageBox.Show("You are not authorised for Target Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                                    ENTSTAT_ComboEditor.Focus()
                                    Exit Function
                                End If
                            End If

                            ' Else

                            ' End If
                        End If
                    End If
                End If

                ' Validate_Data()
                If LMode = "FIND" Then
                    If Mid(drv1("TARMODE"), 1, 1) <> "S" Then
                        Validate_Data()
                    End If
                Else
                    Validate_Data()
                End If

                If LMode = "NEW" Then
                    dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, TCS_Lib.Get_Acyear, 0, MainForm.UserID)
                    dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

                    If itm_save = True And (TARMODE_UltraComboEditor.SelectedIndex = 1 Or TARMODE_UltraComboEditor.SelectedIndex = 4 Or TARMODE_UltraComboEditor.SelectedIndex = 5) Then
                        For i As Integer = 0 To Detail_UltraGrid.Rows.Count - 1
                            drvitm_sav = dvitm_sav.AddNew
                            drvitm_sav.BeginEdit()
                            drvitm_sav("REFYEAR") = dvTRA(0)("REFYEAR")
                            drvitm_sav("REFNUMB") = dvTRA(0)("REFNUMB")
                            drvitm_sav("REFSRNO") = dvitm_sav.Count
                            drvitm_sav("ITMCODE") = dvitm(i)("ITMCODE")
                            drvitm_sav("ITMQNTY") = dvitm(i)("qty")
                            drvitm_sav("PM_STATUS") = "N"
                            drvitm_sav("GM_STATUS") = "N"
                            drvitm_sav("BRNCODE") = Branch_UltraComboEditor.Value
                            drvitm_sav.EndEdit()
                        Next

                        dsitm = D_Data.Save_TARGET_REQUEST_ITEM_DataSet(dsitm, MainForm.UserID)
                        dvitm_sav = New DataView(dsitm.TARGET_REQUEST_ITEM)

                        Dim app_path_2 As String = ""
                        app_path_2 = dvitm_sav(0)("REFYEAR") & "-" & dvitm_sav(0)("refnumb")
                        Appl_path = "ftp://172.16.0.159/TARGET_REQUEST/"
                        MakeDir(app_path_2)
                        Dim wc As New System.Net.WebClient
                        For i As Integer = 0 To Detail_UltraGrid.Rows.Count - 1
                            Dim bFile() As Byte = Detail_UltraGrid.Rows(i).Cells("SUPPHTO").Value
                            Dim filename As String = ""
                            Appl_path = "ftp://172.16.0.159/TARGET_REQUEST/" & dvitm_sav(0)("REFYEAR") & "-" & dvitm_sav(0)("refnumb") & "/"
                            filename = dvitm_sav(i)("REFSRNO") & "-" & dvitm_sav(i)("ITMCODE") & ".jpg"
                            Dim Remote_File As String = Appl_path + filename
                            wc.Credentials = New Net.NetworkCredential("ituser", "S0ft@369")
                            wc.UploadData(Remote_File, bFile)
                        Next
                        itm_save = False
                    End If

                    If dvTRA.Count > 0 Then
                        drvTRA = dvTRA(0)
                        MessageBox.Show("Request Saved. New Request No.: " & drvTRA("REFYEAR") & " " & drvTRA("REFNUMB"), "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Mnual_GroupBox.Enabled = False
                        Mnual_GroupBox.Visible = False
                        Detail_UltraGrid.DataSource = Nothing
                        Itmcode_TextEditor.Text = ""
                        TVAl_Label.Text = ""
                        TOEMPSRNO_TextEditor.Text = ""
                        TODETL_UltraLabel.Text = ""
                    Else
                        Exit Function
                    End If
                End If

                If LMode = "FIND" Then

                    '''' Target Insert
                    Dim taryear As String = ""
                    Dim tarnumb As Integer = 0
                    If Convert = "Y" Then
                        If TARMODE_UltraComboEditor.Value = 3 Then
                            dsBTT = New CData.BRANCH_TRANSFER_TARGET_Dataset
                            dvBTT = New DataView(dsBTT.BRANCH_TRANSFER_TARGET)

                            dv = New DataView
                            dv = TCS_Lib.Get_Cmd_View("select buycode from branch where brncode=" & TOBRNCODE_ComboBox.SelectedValue)

                            drvBTT = dvBTT.AddNew
                            drvBTT.BeginEdit()
                            drvBTT("BTTYEAR") = TCS_Lib.Get_Acyear
                            drvBTT("BTTNUMB") = 1
                            drvBTT("BRNCODE") = drvTRA("BRNCODE")
                            drvBTT("SECCODE") = SECCODE_ComboEditor.Value
                            drvBTT("SUPCODE") = dv(0).Item("BUYCODE")
                            drvBTT("BTTVALU") = Val(RTTVALU_TextBox.Text)
                            drvBTT("BTTREAS") = "-"
                            drvBTT("BTTAUTH") = "-"
                            drvBTT("TRENTRY") = "N"
                            drvBTT("ADDUSER") = MainForm.UserID
                            drvBTT("ADDDATE") = Now.Date
                            drvBTT("DELETED") = "N"
                            drvBTT("INVMODE") = RESCODE_ComboEditor.Value
                            drvBTT("BTTMODE") = "B"
                            drvBTT.EndEdit()

                            dsBTT = CData.Save_BRANCH_TRANSFER_TARGET_DataSet(dsBTT, MainForm.UserID)
                            dvBTT = New DataView(dsBTT.BRANCH_TRANSFER_TARGET)
                            drvBTT = dvBTT(0)

                            drvTRA.BeginEdit()
                            drvTRA("TARYEAR") = drvBTT("BTTYEAR")
                            drvTRA("TARNUMB") = drvBTT("BTTNUMB")
                            drvTRA.EndEdit()

                            taryear = drvBTT("BTTYEAR")
                            tarnumb = drvBTT("BTTNUMB")
                        ElseIf TARMODE_UltraComboEditor.Value = 4 Then
                            dsAPT = New CData.APPRX_PURCHASE_TARGET_DataSet
                            dvAPT = New DataView(dsAPT.APPRX_PURCHASE_TARGET)

                            drvAPT = dvAPT.AddNew
                            drvAPT.BeginEdit()
                            drvAPT("APTYEAR") = TCS_Lib.Get_Acyear
                            drvAPT("APTNUMB") = 1
                            drvAPT("BRNCODE") = drvTRA("BRNCODE")
                            drvAPT("SECCODE") = SECCODE_ComboEditor.Value
                            drvAPT("APTQNTY") = Val(RTTQNTY_TextBox.Text)
                            drvAPT("APTVALU") = Val(RTTVALU_TextBox.Text)
                            drvAPT("APTREAS") = "-"
                            drvAPT("APTAUTH") = "-"
                            drvAPT("PRENTRY") = "N"
                            drvAPT("ADDUSER") = MainForm.UserID
                            drvAPT("ADDDATE") = Now.Date
                            drvAPT("APTMODE") = Me.RESCODE_ComboEditor.Value
                            drvAPT.EndEdit()

                            dsAPT = CData.Save_APPRX_PURCHASE_TARGET_DataSet(dsAPT, MainForm.UserID)
                            dvAPT = New DataView(dsAPT.APPRX_PURCHASE_TARGET)
                            drvAPT = dvAPT(0)

                            drvTRA.BeginEdit()
                            drvTRA("TARYEAR") = drvAPT("APTYEAR")
                            drvTRA("TARNUMB") = drvAPT("APTNUMB")
                            drvTRA.EndEdit()

                            taryear = drvAPT("APTYEAR")
                            tarnumb = drvAPT("APTNUMB")
                        Else
                            dsRTT = New CData.RETURN_TARGET_Dataset
                            dvRTT = New DataView(dsRTT.RETURN_TARGET)

                            dv = New DataView
                            dv = TCS_Lib.Get_Cmd_View("select resname from ret_reason where rescode=" & RESCODE_ComboEditor.Value)

                            drvRTT = dvRTT.AddNew
                            drvRTT.BeginEdit()
                            drvRTT("RTTYEAR") = TCS_Lib.Get_Acyear
                            drvRTT("RTTNUMB") = 1
                            drvRTT("BRNCODE") = drvTRA("BRNCODE")
                            drvRTT("SECCODE") = SECCODE_ComboEditor.Value
                            drvRTT("SUPCODE") = SUPCODE_UltraTextEditor.Tag
                            Dim RTMODE As String = ""

                            If TARMODE_UltraComboEditor.Value = 1 Then
                                RTMODE = "R"
                            ElseIf TARMODE_UltraComboEditor.Value = 2 Then
                                RTMODE = "M"
                            ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                                RTMODE = "T"
                                'ElseIf TARMODE_UltraComboEditor.Value = 6 Then
                                '    RTMODE = "E"
                            End If

                            drvRTT("RETMODE") = RTMODE
                            drvRTT("RTTVALU") = Val(RTTVALU_TextBox.Text)
                            drvRTT("RTTREAS") = dv(0).Item("RESNAME")
                            drvRTT("RTTAUTH") = "Y"
                            drvRTT("RTENTRY") = "N"
                            drvRTT("ADDUSER") = MainForm.UserID
                            drvRTT("ADDDATE") = Now.Date
                            drvRTT("DELETED") = "N"
                            drvRTT("BUNYEAR") = BUNYEAR_TextBox.Text.Trim
                            drvRTT("BUNNUMB") = Val(BUNNUMB_TextBox.Text)
                            drvRTT.EndEdit()

                            dsRTT = CData.Save_RETURN_TARGET_Dataset(dsRTT, MainForm.UserID)
                            dvRTT = New DataView(dsRTT.RETURN_TARGET)
                            drvRTT = dvRTT(0)

                            drvTRA.BeginEdit()
                            drvTRA("TARYEAR") = drvRTT("RTTYEAR")
                            drvTRA("TARNUMB") = drvRTT("RTTNUMB")
                            drvTRA.EndEdit()

                            taryear = drvRTT("RTTYEAR")
                            tarnumb = drvRTT("RTTNUMB")
                        End If
                    End If

                    If Mid(drv1("TARMODE"), 1, 1) = "S" Then
                        Dim save_str As String = "update supplier_sample_issue set status='" & Mid(ENTSTAT_ComboEditor.Text, 1, 1) & "' ,edtuser=" & MainForm.UserID & " ,edtdate=sysdate ,PURREMA='" & APPREMA_TextEditor.Text.Trim & "' where brncode=" & drv1("brncode") & " and entyear='" & drv1("REFYEAR") & "' and entnumb=" & drv1("REFNUMB") & "  "
                        Dim dssamp As DataSet
                        dssamp = CData.Get_Dataset(MainForm.UserID, MainForm.Userpass, save_str, dssamp)
                        MessageBox.Show("Entry Saved", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        UltraButton1.PerformClick()
                        Exit Function
                    End If

                    dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, drv1("REFYEAR"), drv1("REFNUMB"), MainForm.UserID)
                    dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
                    If dvTRA.Count > 0 Then
                        drvTRA = dvTRA(0)
                        Dim RYear As String = drvTRA("REFYEAR")
                        Dim RNumb As String = drvTRA("REFNUMB")
                        Dim RSrno As String = drvTRA("REFSRNO")

                        dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
                        dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, RYear, RNumb, RSrno - 1)
                        dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
                        drvTRA = dvTRA(0)

                        If drvTRA("DELETED") = "N" Then
                            drvTRA.BeginEdit()
                            drvTRA("MSGSTAT") = "F"
                            drvTRA.EndEdit()
                            dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, TCS_Lib.Get_Acyear, -1, MainForm.UserID)
                        Else
                            If RSrno - 2 <> 0 Then
                                dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
                                dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, RYear, RNumb, RSrno - 2)
                                dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
                                drvTRA = dvTRA(0)

                                drvTRA.BeginEdit()
                                drvTRA("MSGSTAT") = "F"
                                drvTRA.EndEdit()
                                dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_DataSet(dsTRA, TCS_Lib.Get_Acyear, -1, MainForm.UserID)
                            End If
                        End If

                        If Convert = "Y" Then
                            MessageBox.Show("Entry Saved. New Target No.: " & taryear & " " & tarnumb, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Else
                            MessageBox.Show("Entry Saved", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        End If
                        Target_Request_Approval_Form_Find()
                        Target_Request_Approval_Form_Cancel()
                        Exit Function
                    Else
                        Exit Function
                    End If
                End If

                TARMODE_UltraComboEditor.Enabled = False
                REQSRNO_TextEditor.Enabled = False
                RESCODE_ComboEditor.Enabled = False
                REMA_TextEditor.Enabled = False
                SECCODE_ComboEditor.Enabled = False
                TOBRNCODE_ComboBox.Enabled = False
                BUNYEAR_TextBox.Enabled = False
                BUNNUMB_TextBox.Enabled = False
                REQDETL_UltraLabel.Text = ""
                REQDETL_UltraLabel.Tag = 0

                REQSRNO_TextEditor.Tag = 0
                REQSRNO_TextEditor.Clear()
                REMA_TextEditor.Clear()
                APP_DETAIL_Label.Text = ""
                TRNSUP_UltraLabel.Text = ""
                TRNSUP_UltraLabel.Visible = False
                BUNYEAR_TextBox.Clear()
                BUNNUMB_TextBox.Clear()
                SUPCODE_UltraTextEditor.Text = ""

                ENTSTAT_ComboEditor.Enabled = False
                APPREMA_TextEditor.Enabled = False
                APPREMA_TextEditor.Clear()
                SUMMARY_UltraGrid.DataSource = Nothing
                RTTVALU_TextBox.Enabled = False
                RTTVALU_TextBox.Clear()
                RTTQNTY_TextBox.Enabled = False
                RTTQNTY_TextBox.Clear()
                STAT_ENTMODE_ComboEditor.SelectedIndex = 0
                STAT_ENTMODE_ComboEditor.Enabled = False
                REFYEAR_TextBox.Clear()
                REFNUMB_TextBox.Clear()
                REFYEAR_TextBox.Enabled = False
                REFNUMB_TextBox.Enabled = False
                REQSTATUS_Grid.DataSource = Nothing
                MIS_CheckBox.Checked = False
                MIS_CheckBox.Enabled = False
                PMname_UltraCombo.DataSource = Nothing
                PMname_UltraCombo.Enabled = False
                BRANCH_UltraLabel.Text = ""
                Branch_UltraComboEditor.DataSource = Nothing
                Branch_UltraComboEditor.Enabled = False
                bcode = 0
                ecode = 0
                dcode = 0
                toecode = 0
                todcode = 0
                MIS = "N"
                Return True
            End If

        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Function

    'Public Function Target_Request_Approval_Form_Save() As Boolean
    '    Try
    '        If LMode = "NEW" Then
    '            If TARMODE_UltraComboEditor.Value = 1 Then
    '                If BUNYEAR_TextBox.Text.Trim.Length <> 7 Then
    '                    MessageBox.Show("Enter Valid A/c Year", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    BUNYEAR_TextBox.Focus()
    '                    Exit Function
    '                End If

    '                If Val(BUNNUMB_TextBox.Text) = 0 Then
    '                    MessageBox.Show("Enter Valid Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    BUNNUMB_TextBox.Focus()
    '                    Exit Function
    '                End If

    '                dv = New DataView
    '                dv = TCS_Lib.Get_Cmd_View("select bunopen from packing_slip where pacyear='" & BUNYEAR_TextBox.Text.Trim & "' and pacnumb=" & Val(BUNNUMB_TextBox.Text) & " and deleted='N'")
    '                If dv.Count > 0 Then
    '                    If dv(0).Item("BUNOPEN") = "N" Then
    '                        MessageBox.Show("Entered Bundle Number is not Opened", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                        Exit Function
    '                    End If

    '                    dv = New DataView
    '                    dv = TCS_Lib.Get_Cmd_View("select accstat from pur_det_payment det,bundle_summary bsum where det.puryear=bsum.puryear and det.purcono=bsum.purcono and bsum.bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bsum.bunnumb=" & Val(BUNNUMB_TextBox.Text) & " and bsum.deleted='N'")
    '                    If dv.Count > 0 Then
    '                        If dv(0).Item("ACCSTAT") = "Y" Then
    '                            MessageBox.Show("Already Account Payment Pass Entry Made for this entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                            Exit Function
    '                        End If
    '                    End If

    '                    dv = New DataView
    '                    dv = TCS_Lib.Get_Cmd_View("select purpaid from pur_det det,bundle_summary bsum where det.puryear=bsum.puryear and det.purcono=bsum.purcono and bsum.bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bsum.bunnumb=" & Val(BUNNUMB_TextBox.Text) & " and bsum.deleted='N' and det.deleted='N'")
    '                    If dv.Count > 0 Then
    '                        If dv(0).Item("PURPAID") = "Y" Then
    '                            MessageBox.Show("Already Payment Paid for this entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                            Exit Function
    '                        End If
    '                    End If

    '                Else
    '                    MessageBox.Show("Enter Valid Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    Exit Function
    '                End If


    '                '' Update Date Lock

    '                Dim DvUpd_DateChk As DataView
    '                DvUpd_DateChk = New DataView
    '                DvUpd_DateChk = TCS_Lib.Get_Cmd_View("select brncode,bunyear,bunnumb,trunc(sysdate)-trunc(adddate) elg_date from stock_bundle_summary where brncode=" & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)) & " and bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bunnumb=" & Val(BUNNUMB_TextBox.Text))

    '                If DvUpd_DateChk.Count = 0 Then
    '                    MessageBox.Show("Verify Entered Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    Exit Function
    '                Else
    '                    If DvUpd_DateChk(0).Item("elg_date") > 1 Then
    '                        MessageBox.Show("Debit Request Can't Be Made For This Bundle Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                        Exit Function
    '                    End If
    '                End If

    '            ElseIf TARMODE_UltraComboEditor.Value = 2 Then
    '                If WOS_CheckBox.Checked = False Then
    '                    If BUNYEAR_TextBox.Text.Trim.Length <> 7 Then
    '                        MessageBox.Show("Enter Valid A/c Year", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                        BUNYEAR_TextBox.Focus()
    '                        Exit Function
    '                    End If

    '                    If Val(BUNNUMB_TextBox.Text) = 0 Then
    '                        MessageBox.Show("Enter Valid PJV Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                        BUNNUMB_TextBox.Focus()
    '                        Exit Function
    '                    End If

    '                    If Val(RTTVALU_TextBox.Text) > ManRet_Tar Then
    '                        MessageBox.Show("Target Amount Exceed than Selected Invoice Amount", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                        RTTVALU_TextBox.Clear()
    '                        RTTVALU_TextBox.Focus()
    '                        Exit Function
    '                    End If
    '                End If
    '                Dim dvPJV As New DataView
    '                dvPJV = TCS_Lib.Get_Cmd_View(" Select Count(*) Cnt From  Trandata.Manual_Return_Slip_Summary@tcscentr Where  Puryear='" & BUNYEAR_TextBox.Text & "' And Purcono=" & Val(BUNNUMB_TextBox.Text) & " And (Brncode,Mrtyear,Mrtnumb) Not in " & _
    '                                             " (Select Brncode,Mrtyear,Mrtnumb From  Trandata.Manual_Return_Summary@tcscentr  Where Puryear='" & BUNYEAR_TextBox.Text & "' And Purcono=" & Val(BUNNUMB_TextBox.Text) & ")")
    '                If dvPJV(0)("Cnt") > 0 Then
    '                    MessageBox.Show("For this PJV Already a Manual Return Slip Was Pending...! Enter Valid PJV Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    BUNNUMB_TextBox.Focus()
    '                    Exit Function
    '                End If
    '                If SUPCODE_UltraTextEditor.Tag = 0 Then
    '                    MessageBox.Show("Enter Valid Supplier Code", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    SUPCODE_UltraTextEditor.Clear()
    '                    SUPCODE_UltraTextEditor.Focus()
    '                    Exit Function
    '                End If
    '            End If

    '            If Val(RTTVALU_TextBox.Text) = 0 Then
    '                MessageBox.Show("Enter Valid Target Value", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                RTTVALU_TextBox.Focus()
    '                Exit Function
    '            End If

    '            If REQSRNO_TextEditor.Tag = 0 Then
    '                MessageBox.Show("Enter Valid Request By Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                REQSRNO_TextEditor.Focus()
    '                Exit Function
    '            End If

    '            If RESCODE_ComboEditor.Value = Nothing Then
    '                MessageBox.Show("Select Valid Reason", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '                RESCODE_ComboEditor.Focus()
    '                Exit Function
    '            End If
    '        End If


    '        If MessageBox.Show("Do you want to Save", "TCS Centra - Save?", MessageBoxButtons.YesNo, MessageBoxIcon.Question) = Windows.Forms.DialogResult.Yes Then

    '            'If LMode = "FIND" Then

    '            '    Exit Function
    '            'End If


    '            If Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "O" Then
    '                If Mid(drv1("TARMODE"), 1, 1) <> "S" Then
    '                    If Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "P" And (Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "R") Then
    '                        If DES_ComboEditor.Value = 5 Then
    '                            MessageBox.Show("You are not authorised for Target Request Mis Dept Change To GM Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                            ENTSTAT_ComboEditor.Focus()
    '                            If Mid(drv1("TARMODE"), 1, 1) = "M" Or Mid(drv1("TARMODE"), 1, 1) = "R" Then
    '                                If GM = "Y" Then
    '                                    Convert = "Y"
    '                                Else
    '                                    ' If DES_ComboEditor.Value = 5 Then
    '                                    MessageBox.Show("You are not authorised for Target Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                                    ENTSTAT_ComboEditor.Focus()
    '                                    Exit Function
    '                                    'End If
    '                                End If

    '                            End If
    '                            If Mid(drv1("TARMODE"), 1, 1) <> "M" And Mid(drv1("TARMODE"), 1, 1) <> "R" Then
    '                                If MIS = "Y" Then
    '                                    Convert = "Y"
    '                                Else
    '                                    MessageBox.Show("You are not authorised for Target Approval", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                                    ENTSTAT_ComboEditor.Focus()
    '                                    Exit Function
    '                                End If
    '                            End If

    '                            ' Else

    '                            ' End If
    '                        End If
    '                    End If
    '                End If
    '            End If
    '            ' Validate_Data()
    '            If LMode = "FIND" Then
    '                If Mid(drv1("TARMODE"), 1, 1) <> "S" Then
    '                    Validate_Data()
    '                End If
    '            Else
    '                Validate_Data()
    '            End If

    '            If LMode = "NEW" Then
    '                dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, TCS_Lib.Get_Acyear, 0, MainForm.UserID)
    '                dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

    '                If itm_save = True And TARMODE_UltraComboEditor.SelectedIndex = 1 Then
    '                    For i As Integer = 0 To Detail_UltraGrid.Rows.Count - 1
    '                        drvitm_sav = dvitm_sav.AddNew
    '                        drvitm_sav.BeginEdit()
    '                        drvitm_sav("REFYEAR") = dvTRA(0)("REFYEAR")
    '                        drvitm_sav("REFNUMB") = dvTRA(0)("REFNUMB")
    '                        drvitm_sav("REFSRNO") = dvitm_sav.Count
    '                        drvitm_sav("ITMCODE") = dvitm(i)("ITMCODE")
    '                        drvitm_sav("ITMQNTY") = dvitm(i)("qty")
    '                        drvitm_sav("PM_STATUS") = "N"
    '                        drvitm_sav("GM_STATUS") = "N"
    '                        ''drvitm_sav("BRNCODE") = MainForm.USER_BRNCODE
    '                        drvitm_sav("BRNCODE") = TCS_Lib.Get_Emp_Brncode(MainForm.UserID)
    '                        drvitm_sav.EndEdit()
    '                    Next

    '                    dsitm = D_Data.Save_TARGET_REQUEST_ITEM_DataSet(dsitm, MainForm.UserID)
    '                    dvitm_sav = New DataView(dsitm.TARGET_REQUEST_ITEM)
    '                    itm_save = False
    '                End If

    '                If dvTRA.Count > 0 Then
    '                    drvTRA = dvTRA(0)
    '                    MessageBox.Show("Request Saved. New Request No.: " & drvTRA("REFYEAR") & " " & drvTRA("REFNUMB"), "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    Mnual_GroupBox.Enabled = False
    '                    Mnual_GroupBox.Visible = False
    '                    Detail_UltraGrid.DataSource = Nothing
    '                    Itmcode_TextEditor.Text = ""
    '                    TVAl_Label.Text = ""
    '                    TOEMPSRNO_TextEditor.Text = ""
    '                    TODETL_UltraLabel.Text = ""
    '                Else
    '                    Exit Function
    '                End If
    '            End If

    '            If LMode = "FIND" Then
    '                '''' Target Insert
    '                Dim taryear As String = ""
    '                Dim tarnumb As Integer = 0
    '                If Convert = "Y" Then
    '                    If TARMODE_UltraComboEditor.Value = 3 Then
    '                        dsBTT = New CData.BRANCH_TRANSFER_TARGET_Dataset
    '                        dvBTT = New DataView(dsBTT.BRANCH_TRANSFER_TARGET)

    '                        dv = New DataView
    '                        dv = TCS_Lib.Get_Cmd_View("select buycode from branch where brncode=" & TOBRNCODE_ComboBox.SelectedValue)

    '                        drvBTT = dvBTT.AddNew
    '                        drvBTT.BeginEdit()
    '                        drvBTT("BTTYEAR") = TCS_Lib.Get_Acyear
    '                        drvBTT("BTTNUMB") = 1
    '                        drvBTT("BRNCODE") = drvTRA("BRNCODE")
    '                        drvBTT("SECCODE") = SECCODE_ComboEditor.Value
    '                        drvBTT("SUPCODE") = dv(0).Item("BUYCODE")
    '                        drvBTT("BTTVALU") = Val(RTTVALU_TextBox.Text)
    '                        drvBTT("BTTREAS") = "-"
    '                        drvBTT("BTTAUTH") = "-"
    '                        drvBTT("TRENTRY") = "N"
    '                        drvBTT("ADDUSER") = MainForm.UserID
    '                        drvBTT("ADDDATE") = Now.Date
    '                        drvBTT("DELETED") = "N"
    '                        drvBTT("INVMODE") = RESCODE_ComboEditor.Value
    '                        drvBTT("BTTMODE") = "B"
    '                        drvBTT.EndEdit()

    '                        dsBTT = CData.Save_BRANCH_TRANSFER_TARGET_DataSet(dsBTT, MainForm.UserID)
    '                        dvBTT = New DataView(dsBTT.BRANCH_TRANSFER_TARGET)
    '                        drvBTT = dvBTT(0)

    '                        drvTRA.BeginEdit()
    '                        drvTRA("TARYEAR") = drvBTT("BTTYEAR")
    '                        drvTRA("TARNUMB") = drvBTT("BTTNUMB")
    '                        drvTRA.EndEdit()

    '                        taryear = drvBTT("BTTYEAR")
    '                        tarnumb = drvBTT("BTTNUMB")
    '                    ElseIf TARMODE_UltraComboEditor.Value = 4 Then
    '                        dsAPT = New CData.APPRX_PURCHASE_TARGET_Dataset
    '                        dvAPT = New DataView(dsAPT.APPRX_PURCHASE_TARGET)

    '                        drvAPT = dvAPT.AddNew
    '                        drvAPT.BeginEdit()
    '                        drvAPT("APTYEAR") = TCS_Lib.Get_Acyear
    '                        drvAPT("APTNUMB") = 1
    '                        drvAPT("BRNCODE") = drvTRA("BRNCODE")
    '                        drvAPT("SECCODE") = SECCODE_ComboEditor.Value
    '                        drvAPT("APTQNTY") = Val(RTTQNTY_TextBox.Text)
    '                        drvAPT("APTVALU") = Val(RTTVALU_TextBox.Text)
    '                        drvAPT("APTREAS") = "-"
    '                        drvAPT("APTAUTH") = "-"
    '                        drvAPT("PRENTRY") = "N"
    '                        drvAPT("ADDUSER") = MainForm.UserID
    '                        drvAPT("ADDDATE") = Now.Date
    '                        drvAPT("APTMODE") = Me.RESCODE_ComboEditor.Value
    '                        drvAPT.EndEdit()

    '                        dsAPT = CData.Save_APPRX_PURCHASE_TARGET_DataSet(dsAPT, MainForm.UserID)
    '                        dvAPT = New DataView(dsAPT.APPRX_PURCHASE_TARGET)
    '                        drvAPT = dvAPT(0)

    '                        drvTRA.BeginEdit()
    '                        drvTRA("TARYEAR") = drvAPT("APTYEAR")
    '                        drvTRA("TARNUMB") = drvAPT("APTNUMB")
    '                        drvTRA.EndEdit()

    '                        taryear = drvAPT("APTYEAR")
    '                        tarnumb = drvAPT("APTNUMB")
    '                    Else
    '                        dsRTT = New CData.RETURN_TARGET_Dataset
    '                        dvRTT = New DataView(dsRTT.RETURN_TARGET)

    '                        dv = New DataView
    '                        dv = TCS_Lib.Get_Cmd_View("select resname from ret_reason where rescode=" & RESCODE_ComboEditor.Value)

    '                        drvRTT = dvRTT.AddNew
    '                        drvRTT.BeginEdit()
    '                        drvRTT("RTTYEAR") = TCS_Lib.Get_Acyear
    '                        drvRTT("RTTNUMB") = 1
    '                        drvRTT("BRNCODE") = drvTRA("BRNCODE")
    '                        drvRTT("SECCODE") = SECCODE_ComboEditor.Value
    '                        drvRTT("SUPCODE") = SUPCODE_UltraTextEditor.Tag
    '                        drvRTT("RETMODE") = IIf(TARMODE_UltraComboEditor.Value = 1, "R", "M")
    '                        drvRTT("RTTVALU") = Val(RTTVALU_TextBox.Text)
    '                        drvRTT("RTTREAS") = dv(0).Item("RESNAME")
    '                        drvRTT("RTTAUTH") = "Y"
    '                        drvRTT("RTENTRY") = "N"
    '                        drvRTT("ADDUSER") = MainForm.UserID
    '                        drvRTT("ADDDATE") = Now.Date
    '                        drvRTT("DELETED") = "N"
    '                        drvRTT("BUNYEAR") = BUNYEAR_TextBox.Text.Trim
    '                        drvRTT("BUNNUMB") = Val(BUNNUMB_TextBox.Text)
    '                        drvRTT.EndEdit()

    '                        dsRTT = CData.Save_RETURN_TARGET_Dataset(dsRTT, MainForm.UserID)
    '                        dvRTT = New DataView(dsRTT.RETURN_TARGET)
    '                        drvRTT = dvRTT(0)

    '                        drvTRA.BeginEdit()
    '                        drvTRA("TARYEAR") = drvRTT("RTTYEAR")
    '                        drvTRA("TARNUMB") = drvRTT("RTTNUMB")
    '                        drvTRA.EndEdit()

    '                        taryear = drvRTT("RTTYEAR")
    '                        tarnumb = drvRTT("RTTNUMB")
    '                    End If
    '                End If
    '                If Mid(drv1("TARMODE"), 1, 1) = "S" Then
    '                    Dim save_str As String = "update supplier_sample_issue set status='" & Mid(ENTSTAT_ComboEditor.Text, 1, 1) & "' ,edtuser=" & MainForm.UserID & " ,edtdate=sysdate ,PURREMA='" & APPREMA_TextEditor.Text.Trim & "' where brncode=" & drv1("brncode") & " and entyear='" & drv1("REFYEAR") & "' and entnumb=" & drv1("REFNUMB") & "  "
    '                    Dim dssamp As DataSet
    '                    dssamp = CData.Get_DataSet(MainForm.UserID, MainForm.Userpass, save_str, dssamp)
    '                    MessageBox.Show("Entry Saved", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    UltraButton1.PerformClick()
    '                    Exit Function
    '                End If
    '                dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, drv1("REFYEAR"), drv1("REFNUMB"), MainForm.UserID)
    '                dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
    '                If dvTRA.Count > 0 Then
    '                    drvTRA = dvTRA(0)
    '                    Dim RYear As String = drvTRA("REFYEAR")
    '                    Dim RNumb As String = drvTRA("REFNUMB")
    '                    Dim RSrno As String = drvTRA("REFSRNO")

    '                    dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
    '                    dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, RYear, RNumb, RSrno - 1)
    '                    dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
    '                    drvTRA = dvTRA(0)

    '                    If drvTRA("DELETED") = "N" Then
    '                        drvTRA.BeginEdit()
    '                        drvTRA("MSGSTAT") = "F"
    '                        drvTRA.EndEdit()
    '                        dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, TCS_Lib.Get_Acyear, -1, MainForm.UserID)
    '                    Else
    '                        If RSrno - 2 <> 0 Then
    '                            dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
    '                            dsTRA = CData.Get_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, RYear, RNumb, RSrno - 2)
    '                            dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)
    '                            drvTRA = dvTRA(0)

    '                            drvTRA.BeginEdit()
    '                            drvTRA("MSGSTAT") = "F"
    '                            drvTRA.EndEdit()
    '                            dsTRA = CData.Save_TARGET_REQUEST_APPROVAL_Dataset(dsTRA, TCS_Lib.Get_Acyear, -1, MainForm.UserID)
    '                        End If
    '                    End If

    '                    If Convert = "Y" Then
    '                        MessageBox.Show("Entry Saved. New Target No.: " & taryear & " " & tarnumb, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    Else
    '                        MessageBox.Show("Entry Saved", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                    End If
    '                    Target_Request_Approval_Form_Find()
    '                    Target_Request_Approval_Form_Cancel()
    '                    Exit Function
    '                Else
    '                    Exit Function
    '                End If
    '            End If

    '            TARMODE_UltraComboEditor.Enabled = False
    '            REQSRNO_TextEditor.Enabled = False
    '            RESCODE_ComboEditor.Enabled = False
    '            REMA_TextEditor.Enabled = False
    '            SECCODE_ComboEditor.Enabled = False
    '            TOBRNCODE_ComboBox.Enabled = False
    '            BUNYEAR_TextBox.Enabled = False
    '            BUNNUMB_TextBox.Enabled = False
    '            REQDETL_UltraLabel.Text = ""
    '            REQDETL_UltraLabel.Tag = 0

    '            REQSRNO_TextEditor.Tag = 0
    '            REQSRNO_TextEditor.Clear()
    '            REMA_TextEditor.Clear()
    '            APP_DETAIL_Label.Text = ""
    '            BUNYEAR_TextBox.Clear()
    '            BUNNUMB_TextBox.Clear()
    '            SUPCODE_UltraTextEditor.Text = ""

    '            ENTSTAT_ComboEditor.Enabled = False
    '            APPREMA_TextEditor.Enabled = False
    '            APPREMA_TextEditor.Clear()
    '            SUMMARY_UltraGrid.DataSource = Nothing
    '            RTTVALU_TextBox.Enabled = False
    '            RTTVALU_TextBox.Clear()
    '            RTTQNTY_TextBox.Enabled = False
    '            RTTQNTY_TextBox.Clear()
    '            STAT_ENTMODE_ComboEditor.SelectedIndex = 0
    '            STAT_ENTMODE_ComboEditor.Enabled = False
    '            REFYEAR_TextBox.Clear()
    '            REFNUMB_TextBox.Clear()
    '            REFYEAR_TextBox.Enabled = False
    '            REFNUMB_TextBox.Enabled = False
    '            REQSTATUS_Grid.DataSource = Nothing
    '            MIS_CheckBox.Checked = False
    '            MIS_CheckBox.Enabled = False

    '            BRANCH_UltraLabel.Text = ""
    '            bcode = 0
    '            ecode = 0
    '            dcode = 0
    '            toecode = 0
    '            todcode = 0
    '            MIS = "N"
    '            Return True
    '            'End If
    '        End If
    '    Catch ex As Exception
    '        MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    End Try
    'End Function

    Private Sub Validate_Data()

        Dim RTM As String = ""

        If LMode = "NEW" Then

            Dim charactersDisallowed As String = "ABCDEFGHIJKLMNOPQRSTUVWXYZ-.@ "
            Dim theText As String = REQSRNO_TextEditor.Text.ToUpper
            Dim Letter As String
            'theText = theText.Replace(EMPCODE_UltraTextEditor.Text.ToLower, String.Empty)

            For x As Integer = 0 To REQSRNO_TextEditor.Text.Length - 1
                Letter = REQSRNO_TextEditor.Text.Substring(x, 1)
                If charactersDisallowed.Contains(Letter) Then
                    theText = theText.Replace(Letter, String.Empty)
                End If
            Next

            Dim theText1 As String = TOEMPSRNO_TextEditor.Text.ToUpper
            Dim Letter1 As String
            'theText = theText.Replace(EMPCODE_UltraTextEditor.Text.ToLower, String.Empty)

            For x As Integer = 0 To TOEMPSRNO_TextEditor.Text.Length - 1
                Letter1 = TOEMPSRNO_TextEditor.Text.Substring(x, 1)
                If charactersDisallowed.Contains(Letter1) Then
                    theText1 = theText1.Replace(Letter1, String.Empty)
                End If
            Next


            drvTRA = dvTRA.AddNew
            drvTRA.BeginEdit()
            drvTRA("BRNCODE") = Branch_UltraComboEditor.Value
            drvTRA("REFYEAR") = TCS_Lib.Get_Acyear
            drvTRA("REFNUMB") = 1
            drvTRA("REFSRNO") = 1
            drvTRA("EMPCODE") = theText.Trim ' Mid(REQSRNO_TextEditor.Text, 1, 4)
            drvTRA("EMPSRNO") = REQSRNO_TextEditor.Tag
            drvTRA("EMPNAME") = Mid(REQSRNO_TextEditor.Text, 6, REQSRNO_TextEditor.Text.Trim.Length)
            drvTRA("DESCODE") = dcode
            drvTRA("ESECODE") = ecode
            drvTRA("ENTDATE") = Now.Date

            If MIS_CheckBox.Checked = True Then
                drvTRA("TO_DEPT") = "MIS"
            Else
                drvTRA("TO_EMPSRNO") = TOEMPSRNO_TextEditor.Tag
                drvTRA("TO_EMPCODE") = theText1.Trim 'Mid(TOEMPSRNO_TextEditor.Text, 1, 4)
                drvTRA("TO_EMPNAME") = Mid(TOEMPSRNO_TextEditor.Text, 6, TOEMPSRNO_TextEditor.Text.Trim.Length)
                drvTRA("TO_DESCODE") = todcode
                drvTRA("TO_ESECODE") = toecode
                If TARMODE_UltraComboEditor.Value = 5 Then
                    drvTRA("TO_DEPT") = "MIS"
                End If
            End If

            'drvTRA("TARMODE") = IIf(TARMODE_UltraComboEditor.Value = 1, "R", IIf(TARMODE_UltraComboEditor.Value = 2, "M", IIf(TARMODE_UltraComboEditor.Value = 3, "B", "A")))
            If TARMODE_UltraComboEditor.Value = 1 Then
                RTM = "R"
            ElseIf TARMODE_UltraComboEditor.Value = 2 Then
                RTM = "M"
            ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                RTM = "B"
            ElseIf TARMODE_UltraComboEditor.Value = 4 Then
                RTM = "A"
            ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                RTM = "T"
            ElseIf TARMODE_UltraComboEditor.Value = 6 Then
                RTM = "E"
            End If
            drvTRA("TARMODE") = RTM
            drvTRA("RESCODE") = RESCODE_ComboEditor.Value

            If TARMODE_UltraComboEditor.Value = 1 Then

                If WOS_CheckBox.Checked = False Then
                    dv = New DataView
                    dv = TCS_Lib.Get_Cmd_View("select det.supcode from bundle_summary bsum,pur_det det where bsum.puryear=det.puryear and bsum.purcono=det.purcono and bsum.deleted='N' and det.deleted='N' and bsum.bunyear='" & BUNYEAR_TextBox.Text.Trim & "' and bsum.bunnumb=" & Val(BUNNUMB_TextBox.Text))

                    SUPCODE_UltraTextEditor.Text = dv(0).Item("SUPCODE")
                    SUPCODE_UltraTextEditor.Tag = dv(0).Item("SUPCODE")
                    drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
                Else
                    drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
                End If

            ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
            End If

            If TARMODE_UltraComboEditor.Value = 2 Then
                drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
            End If

            If TARMODE_UltraComboEditor.Value = 6 Then
                drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Text
            End If

            If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then
                drvTRA("BUNYEAR") = BUNYEAR_TextBox.Text.Trim
                drvTRA("BUNNUMB") = Val(BUNNUMB_TextBox.Text)
            ElseIf TARMODE_UltraComboEditor.Value = 5 And Val(BUNNUMB_TextBox.Text) > 0 Then
                drvTRA("BUNYEAR") = BUNYEAR_TextBox.Text.Trim
                drvTRA("BUNNUMB") = Val(BUNNUMB_TextBox.Text)
            ElseIf TARMODE_UltraComboEditor.Value = 6 Then
                drvTRA("BUNYEAR") = "-"
                drvTRA("BUNNUMB") = 0
            End If

            If TARMODE_UltraComboEditor.Value = 3 Then
                drvTRA("TO_BRNCODE") = TOBRNCODE_ComboBox.SelectedValue
            End If

            drvTRA("SECCODE") = SECCODE_ComboEditor.Value
            drvTRA("RTTVALUE") = Val(RTTVALU_TextBox.Text)
            If TARMODE_UltraComboEditor.Value = 4 Or TARMODE_UltraComboEditor.Value = 5 Or TARMODE_UltraComboEditor.Value = 6 Then
                drvTRA("RTTQNTY") = Val(RTTQNTY_TextBox.Text)
            End If
            drvTRA("ENT_REMARK") = REMA_TextEditor.Text.Trim
            drvTRA("ENTSTAT") = "P"
            drvTRA("MSGSTAT") = "S"
            drvTRA("ADDUSER") = MainForm.UserID
            drvTRA("ADDDATE") = Now.Now
            drvTRA("DELETED") = "N"
            drvTRA.EndEdit()
        Else
            dsTRA = New CData.TARGET_REQUEST_APPROVAL_Dataset
            dvTRA = New DataView(dsTRA.TARGET_REQUEST_APPROVAL)

            drvTRA = dvTRA.AddNew
            drvTRA.BeginEdit()
            drvTRA("BRNCODE") = drv1("BRNCODE")
            drvTRA("REFYEAR") = drv1("REFYEAR")
            drvTRA("REFNUMB") = drv1("REFNUMB")
            drvTRA("REFSRNO") = 1

            dv = New DataView
            dv = TCS_Lib.Get_Cmd_View("select nvl(to_dept,'-') to_dept,nvl(to_empsrno,0) to_empsrno,to_empcode,to_empname,to_esecode,to_descode from target_request_approval where refyear='" & drv1("REFYEAR") & "' and to_number(refnumb)=" & drv1("REFNUMB") & " and to_number(refsrno) =(select max(to_number(refsrno)) from target_request_approval where refyear='" & drv1("REFYEAR") & "' and to_number(refnumb)=" & drv1("REFNUMB") & ")")

            If dv(0).Item("TO_EMPSRNO") = 0 Then
                drvTRA("EMPCODE") = 0
                drvTRA("EMPSRNO") = 0
                drvTRA("EMPNAME") = dv(0).Item("TO_DEPT")
                drvTRA("ESECODE") = 0
                drvTRA("DESCODE") = 0
            Else
                drvTRA("EMPCODE") = dv(0).Item("TO_EMPCODE")
                drvTRA("EMPSRNO") = dv(0).Item("TO_EMPSRNO")
                drvTRA("EMPNAME") = dv(0).Item("TO_EMPNAME")
                drvTRA("ESECODE") = dv(0).Item("TO_ESECODE")
                drvTRA("DESCODE") = dv(0).Item("TO_DESCODE")
            End If
            drvTRA("ENTDATE") = Now.Date

            If ENTSTAT_ComboEditor.SelectedIndex = 0 Then
                If DES_ComboEditor.Value < 5 Then
                    drvTRA("TO_EMPCODE") = Mid(TOREQ_ComboEditor.Text, 1, 4)
                    drvTRA("TO_EMPSRNO") = TOREQ_ComboEditor.SelectedValue
                    drvTRA("TO_EMPNAME") = Mid(TOREQ_ComboEditor.Text, 6, TOREQ_ComboEditor.Text.Length)
                    drvTRA("TO_ESECODE") = 0
                    drvTRA("TO_DESCODE") = 0
                Else
                    drvTRA("TO_EMPCODE") = 0
                    drvTRA("TO_EMPSRNO") = 0
                    drvTRA("TO_EMPNAME") = "-"
                    drvTRA("TO_ESECODE") = 0
                    drvTRA("TO_DESCODE") = 0
                    drvTRA("TO_DEPT") = "MIS"
                End If
            Else
                drvTRA("TO_EMPCODE") = 0
                drvTRA("TO_EMPSRNO") = 0
                drvTRA("TO_EMPNAME") = "-"
                drvTRA("TO_ESECODE") = 0
                drvTRA("TO_DESCODE") = 0
            End If


            If TARMODE_UltraComboEditor.Value = 1 Then
                RTM = "R"
            ElseIf TARMODE_UltraComboEditor.Value = 2 Then
                RTM = "M"
            ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                RTM = "B"
            ElseIf TARMODE_UltraComboEditor.Value = 4 Then
                RTM = "A"
            ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                RTM = "T"
                'ElseIf TARMODE_UltraComboEditor.Value = 6 Then
                '    RTM = "E"
            End If

            drvTRA("TARMODE") = RTM 'IIf(TARMODE_UltraComboEditor.Value = 1, "R", IIf(TARMODE_UltraComboEditor.Value = 2, "M", IIf(TARMODE_UltraComboEditor.Value = 3, "B", "A")))
            drvTRA("RESCODE") = RESCODE_ComboEditor.Value


            If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then
                drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
                drvTRA("BUNYEAR") = BUNYEAR_TextBox.Text.Trim
                drvTRA("BUNNUMB") = Val(BUNNUMB_TextBox.Text)
            ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                drvTRA("SUPCODE") = SUPCODE_UltraTextEditor.Tag
            End If

            If TARMODE_UltraComboEditor.Value = 3 Then
                drvTRA("TO_BRNCODE") = TOBRNCODE_ComboBox.SelectedValue
            End If

            drvTRA("SECCODE") = SECCODE_ComboEditor.Value
            drvTRA("RTTVALUE") = Val(RTTVALU_TextBox.Text)
            If TARMODE_UltraComboEditor.Value = 4 Or TARMODE_UltraComboEditor.Value = 5 Then
                drvTRA("RTTQNTY") = Val(RTTQNTY_TextBox.Text)
            End If
            drvTRA("ENT_REMARK") = REMA_TextEditor.Text.Trim
            drvTRA("APP_REMARK") = APPREMA_TextEditor.Text.Trim
            drvTRA("ENTSTAT") = Mid(ENTSTAT_ComboEditor.Text, 1, 1)
            drvTRA("MSGSTAT") = IIf(Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "R", "R", IIf(Mid(ENTSTAT_ComboEditor.Text, 1, 1) = "O", "O", "S"))
            drvTRA("ADDUSER") = MainForm.UserID
            drvTRA("ADDDATE") = Now.Now
            drvTRA("DELETED") = "N"
            drvTRA.EndEdit()
        End If
    End Sub

    Private Sub MIS_CheckBox_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MIS_CheckBox.CheckedChanged
        If MIS_CheckBox.Checked = True Then
            TOEMPSRNO_TextEditor.Clear()
            TOEMPSRNO_TextEditor.Enabled = False
        Else
            TOEMPSRNO_TextEditor.Clear()
            TOEMPSRNO_TextEditor.Enabled = True
            TOEMPSRNO_TextEditor.Focus()
        End If
    End Sub

    'Private Sub TOEMPSRNO_TextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles TOEMPSRNO_TextEditor.KeyDown
    '    Try
    '        If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
    '            dv = New DataView
    '            dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
    '            If dv.Count > 0 Then

    '                TOEMPSRNO_TextEditor.Text = dv(0).Item("EMPCODE") & "-" & dv(0).Item("EMPNAME")
    '                TOEMPSRNO_TextEditor.Tag = dv(0).Item("EMPSRNO")

    '                TODETL_UltraLabel.Text = "Branch: " & dv(0).Item("BRNNAME") & vbCrLf & vbCrLf & "Section: " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "Designation: " & dv(0).Item("DESNAME")
    '                TODETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

    '                toecode = dv(0).Item("ESECODE")
    '                todcode = dv(0).Item("DESCODE")
    '            Else
    '                MessageBox.Show("Enter Valid Employee Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
    '                TOEMPSRNO_TextEditor.Clear()
    '                TOEMPSRNO_TextEditor.Focus()
    '                Exit Sub
    '            End If
    '        End If
    '    Catch ex As Exception
    '        MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
    '    End Try
    'End Sub

    Private Sub TOEMPSRNO_TextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles TOEMPSRNO_TextEditor.KeyDown
        Try
            If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
                dv = New DataView
                'If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then
                '    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode=136 and sec.seccode=" & SECCODE_ComboEditor.Value & " and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text) & " union select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode=136 and sec.seccode=" & SECCODE_ComboEditor.Value & " and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                'ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                '    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and des.descode in(92,3,96) and brn.brncode=" & IIf(TOBRNCODE_ComboBox.SelectedValue = 100, 888, TOBRNCODE_ComboBox.SelectedValue) & " and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                'Else
                '    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                'End If
                If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then

                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & " and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text) & " union select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and emp.empcode>1000 and  sec.seccode=" & SECCODE_ComboEditor.Value & " and emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                    If TOBRNCODE_ComboBox.SelectedValue = 104 Or TOBRNCODE_ComboBox.SelectedValue = 112 Or TOBRNCODE_ComboBox.SelectedValue = 114 Then
                        EmpStr = " and emp.empcode=5069 and brn.brncode=888"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 102 Then
                        'EmpStr = " and emp.empcode=2358 and brn.brncode=888"
                        EmpStr = " and emp.empcode=12370 and brn.brncode=102"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 888 Or TOBRNCODE_ComboBox.SelectedValue = 100 Or TOBRNCODE_ComboBox.SelectedValue = 103 Or TOBRNCODE_ComboBox.SelectedValue = 201 Then
                        'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                        EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    Else
                        EmpStr = " and des.descode in (92,96,3,75)  and brn.brncode=" & TOBRNCODE_ComboBox.SelectedValue & ""
                    End If
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and  emp.descode=des.descode " & EmpStr & " and emp.empcode>1000 and  emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                    EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode " & EmpStr & " and emp.empcode>1000 and  emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                Else
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empcode>1000 and  emp.empcode=" & Val(TOEMPSRNO_TextEditor.Text))
                End If

                If dv.Count > 0 Then

                    TOEMPSRNO_TextEditor.Text = dv(0).Item("EMPCODE") & "-" & dv(0).Item("EMPNAME")
                    TOEMPSRNO_TextEditor.Tag = dv(0).Item("EMPSRNO")

                    TODETL_UltraLabel.Text = "Branch: " & dv(0).Item("BRNNAME") & vbCrLf & vbCrLf & "Section: " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "Designation: " & dv(0).Item("DESNAME")
                    TODETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

                    toecode = dv(0).Item("ESECODE")
                    todcode = dv(0).Item("DESCODE")
                Else
                    MessageBox.Show("Enter Valid Employee Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    TOEMPSRNO_TextEditor.Clear()
                    TOEMPSRNO_TextEditor.Focus()
                    TODETL_UltraLabel.Text = ""
                    TODETL_UltraLabel.Tag = 0
                    Exit Sub
                End If
                If e.KeyCode = Keys.Back Or e.KeyCode = Keys.Delete Then
                    TOEMPSRNO_TextEditor.Clear()
                    TOEMPSRNO_TextEditor.Focus()
                    TODETL_UltraLabel.Text = ""
                    TODETL_UltraLabel.Tag = 0
                End If
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub TOEMPSRNO_TextEditor_TextChanged(ByVal sender As Object, ByVal e As System.EventArgs) Handles TOEMPSRNO_TextEditor.TextChanged
        Try
            If TOEMPSRNO_TextEditor.Tag > 0 Then
                TOEMPSRNO_TextEditor.Tag = 0
                TOEMPSRNO_TextEditor.Clear()

                toecode = 0
                todcode = 0
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    'Private Sub DES_ComboEditor_ValueChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles DES_ComboEditor.ValueChanged
    '    Dim dvDES As New DataView
    '    If DES_ComboEditor.Value < 5 Then
    '        If DES_ComboEditor.Value = 1 Then
    '            dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (92,96,3) and brncode=" & TOBRNCODE_ComboBox.SelectedValue)
    '        ElseIf DES_ComboEditor.Value = 2 Then
    '            dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where empsrno in (SELECT DISTINCT SEC.EMPSRNO FROM PUR_HEAD_SECTION SEC WHERE SEC.SECCODE=" & SECCODE_ComboEditor.Value & " UNION SELECT DISTINCT SEC.EMPSRNO FROM PUR_GROUP_SECTION SEC WHERE SEC.SECCODE=" & SECCODE_ComboEditor.Value & ")")
    '        ElseIf DES_ComboEditor.Value = 3 Then
    '            dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (132)")
    '        ElseIf DES_ComboEditor.Value = 4 Then
    '            dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (19)")
    '        End If
    '        TOREQ_ComboEditor.DataSource = dvDES
    '        TOREQ_ComboEditor.DisplayMember = "empname"
    '        TOREQ_ComboEditor.ValueMember = "empsrno"
    '        TOREQ_ComboEditor.SelectedIndex = 0

    '        TOREQ_ComboEditor.Enabled = True
    '    Else
    '        TOREQ_ComboEditor.DataSource = Nothing
    '        TOREQ_ComboEditor.Enabled = False
    '    End If
    'End Sub

    Private Sub DES_ComboEditor_ValueChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles DES_ComboEditor.ValueChanged
        Dim dvDES As New DataView
        If DES_ComboEditor.Value < 5 Then
            If DES_ComboEditor.Value = 1 Then
                If IsNothing(TOBRNCODE_ComboBox.SelectedValue) = False Then
                    dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (92,96,3,75) and empcode>1000 and   brncode=" & TOBRNCODE_ComboBox.SelectedValue)
                End If
            ElseIf DES_ComboEditor.Value = 2 Then
                dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where empsrno in (SELECT DISTINCT SEC.EMPSRNO FROM PUR_HEAD_SECTION SEC WHERE SEC.SECCODE=" & SECCODE_ComboEditor.Value & " UNION SELECT DISTINCT SEC.EMPSRNO FROM PUR_GROUP_SECTION SEC WHERE SEC.SECCODE=" & SECCODE_ComboEditor.Value & ")")
            ElseIf DES_ComboEditor.Value = 3 Then
                dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (132) and empcode>1000 ")
            ElseIf DES_ComboEditor.Value = 4 Then
                dvDES = TCS_Lib.Get_Cmd_View("select empsrno,empcode||'-'||empname empname from employee_office where descode in (19,165) and empcode>1000 ")
            End If
            If dvDES.Count > 0 Then
                TOREQ_ComboEditor.DataSource = dvDES
                TOREQ_ComboEditor.DisplayMember = "empname"
                TOREQ_ComboEditor.ValueMember = "empsrno"
                TOREQ_ComboEditor.SelectedIndex = 0

                TOREQ_ComboEditor.Enabled = True
            Else
                TOREQ_ComboEditor.Enabled = False
            End If

        Else
            TOREQ_ComboEditor.DataSource = Nothing
            TOREQ_ComboEditor.Enabled = False
        End If
    End Sub

    Private Sub Timer1_Tick(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Timer1.Tick
        ''Target_Request_Approval_Form_Find()
    End Sub

    Private Sub Target_Request_Approval_Form_Deactivate(ByVal sender As Object, ByVal e As System.EventArgs) Handles Me.Deactivate
        ''  Timer1.Stop()
    End Sub

    Private Sub LOAD_Button_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles LOAD_Button.Click
        If REFYEAR_TextBox.Text.Trim.Length = 0 Then
            MessageBox.Show("Enter Valid A/c Year", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
            REFYEAR_TextBox.Focus()
            Exit Sub
        End If

        If Val(REFNUMB_TextBox.Text) = 0 Then
            MessageBox.Show("Enter Valid Reference Number", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
            REFNUMB_TextBox.Focus()
            Exit Sub
        End If

        Dim dvSTAT As New DataView
        dvSTAT = TCS_Lib.Get_Cmd_View("select substr(brn.nicname,3,3) brnname,req.brncode,req.refyear,req.refnumb,req.refsrno,substr(req.empcode||'-'||req.empname,1,50) empname,ese.esename,des.desname,sec.secname, " & _
                        " decode(nvl(req.supcode,0),0,req.to_brncode,req.supcode) Send_To,req.rttvalue,req.ent_remark,req.app_remark,nvl(req.to_dept,'-') department,DECODE(ENTSTAT,'P','PENDING',DECODE(ENTSTAT,'O','OK','REJECT')) STATUS, REQ.TARYEAR||' '||REQ.TARNUMB TARNUMB " & _
                        " from target_request_approval req,employee_office emp,empsection ese,designation des,section sec,branch brn " & _
                        " where req.deleted='N' and req.empsrno = emp.empsrno(+) And emp.esecode = ese.esecode(+) And emp.descode = des.descode(+) and  " & _
                        " req.seccode=sec.seccode(+) and req.brncode=brn.brncode and req.tarmode='" & Mid(STAT_ENTMODE_ComboEditor.Text, 1, 1) & "' and req.refyear='" & REFYEAR_TextBox.Text.Trim & "' and req.refnumb='" & REFNUMB_TextBox.Text.Trim & "' order by req.refsrno")

        If dvSTAT.Count = 0 Then
            MessageBox.Show("There is No Request Entry Found", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
            REFNUMB_TextBox.Clear()
            REFNUMB_TextBox.Focus()
            Exit Sub
        End If

        Dim drvSTAT As DataRowView
        Dim Cnt As Integer = dvSTAT.Count
        Dim i As Integer = 1
        For Each drvSTAT In dvSTAT
            If i < Cnt Then
                If i = 1 Then
                    drvSTAT("APP_REMARK") = drvSTAT("ENT_REMARK")
                End If

                drvSTAT("STATUS") = "FORWARD"
                i += 1
            End If
        Next

        REQSTATUS_Grid.DataSource = dvSTAT
        REQSTATUS_Grid.DataBind()

        dvSTAT.Table.Columns("BRNNAME").ReadOnly = True
        dvSTAT.Table.Columns("REFSRNO").ReadOnly = True
        dvSTAT.Table.Columns("EMPNAME").ReadOnly = True
        dvSTAT.Table.Columns("SECNAME").ReadOnly = True
        dvSTAT.Table.Columns("SEND_TO").ReadOnly = True
        dvSTAT.Table.Columns("RTTVALUE").ReadOnly = True
        dvSTAT.Table.Columns("APP_REMARK").ReadOnly = True
        dvSTAT.Table.Columns("STATUS").ReadOnly = True
        dvSTAT.Table.Columns("TARNUMB").ReadOnly = True
    End Sub

    Private Sub REQSTATUS_Grid_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles REQSTATUS_Grid.InitializeLayout
        REQSTATUS_Grid.DisplayLayout.Override.CellClickAction = CellClickAction.RowSelect
        REQSTATUS_Grid.DisplayLayout.Override.SelectTypeRow = SelectType.Single
        REQSTATUS_Grid.DisplayLayout.Override.SelectedRowAppearance.FontData.Bold = DefaultableBoolean.True
        REQSTATUS_Grid.DisplayLayout.Override.SelectedRowAppearance.ForeColor = Color.Black

        e.Layout.Bands(0).Columns("BRNCODE").Hidden = True
        e.Layout.Bands(0).Columns("REFYEAR").Hidden = True
        e.Layout.Bands(0).Columns("REFNUMB").Hidden = True
        e.Layout.Bands(0).Columns("ESENAME").Hidden = True
        e.Layout.Bands(0).Columns("DESNAME").Hidden = True
        e.Layout.Bands(0).Columns("ENT_REMARK").Hidden = True
        e.Layout.Bands(0).Columns("DEPARTMENT").Hidden = True

        e.Layout.Bands(0).Columns("BRNNAME").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("REFSRNO").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("EMPNAME").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("SECNAME").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("SEND_TO").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("RTTVALUE").CellAppearance.TextHAlign = HAlign.Right
        e.Layout.Bands(0).Columns("APP_REMARK").CellAppearance.TextHAlign = HAlign.Left
        e.Layout.Bands(0).Columns("STATUS").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("TARNUMB").CellAppearance.TextHAlign = HAlign.Center

        e.Layout.Bands(0).Columns("BRNNAME").Header.Caption = "Branch"
        e.Layout.Bands(0).Columns("REFSRNO").Header.Caption = "Sr.No."
        e.Layout.Bands(0).Columns("EMPNAME").Header.Caption = "Request By Name"
        e.Layout.Bands(0).Columns("SECNAME").Header.Caption = "Section"
        e.Layout.Bands(0).Columns("SEND_TO").Header.Caption = "Supplier / Branch"
        e.Layout.Bands(0).Columns("RTTVALUE").Header.Caption = "Value"
        e.Layout.Bands(0).Columns("APP_REMARK").Header.Caption = "Remark"
        e.Layout.Bands(0).Columns("STATUS").Header.Caption = "Status"
        e.Layout.Bands(0).Columns("TARNUMB").Header.Caption = "Target No."

        e.Layout.Bands(0).Columns("BRNNAME").Width = 100
        e.Layout.Bands(0).Columns("REFSRNO").Width = 75
        e.Layout.Bands(0).Columns("EMPNAME").Width = 200
        e.Layout.Bands(0).Columns("SECNAME").Width = 150
        e.Layout.Bands(0).Columns("SEND_TO").Width = 150
        e.Layout.Bands(0).Columns("RTTVALUE").Width = 100
        e.Layout.Bands(0).Columns("APP_REMARK").Width = 200
        e.Layout.Bands(0).Columns("STATUS").Width = 150
        e.Layout.Bands(0).Columns("TARNUMB").Width = 100
    End Sub

    Private Sub UltraButton1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles UltraButton1.Click

        If MainForm.ValidModifyMenu("USRFIND") = True Then
            Target_Request_Approval_Form_Find()
        Else
            MessageBox.Show("No previllage to Access this Menu", "TCS Accounts", MessageBoxButtons.OK, MessageBoxIcon.Stop)
            Exit Sub

        End If

    End Sub

    Private Sub ok_Button_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles ok_Button.Click
        Try

            If Not Me.Detail_UltraGrid.ActiveRow Is Nothing Then
            Else
                MessageBox.Show("Scan The ITEM Code ..? ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Itmcode_TextEditor.Text = ""
                Itmcode_TextEditor.Focus()
                Exit Sub
            End If

            Dim dv_qty As New DataView
            dv_qty = TCS_Lib.Get_Cmd_View("SELECT * FROM  codeinc ")
            If TARMODE_UltraComboEditor.SelectedIndex <> 3 Then
                If Val(TVAl_Label.Text) < Val(dv_qty(0)("AGSNUMB")) And Val(QTY_Label.Text) < dv_qty(0)("GTSNUMB") Then
                    If Val(TVAl_Label.Text) < Val(dv_qty(0)("AGSNUMB")) Then
                        MessageBox.Show("Target Value Below " & Val(dv_qty(0)("AGSNUMB")) & " ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Itmcode_TextEditor.Text = ""
                        Itmcode_TextEditor.Focus()
                        Exit Sub
                    End If

                    If Val(QTY_Label.Text) < dv_qty(0)("GTSNUMB") Then
                        MessageBox.Show("Target Qty Below " & dv_qty(0)("GTSNUMB") & " ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Itmcode_TextEditor.Text = ""
                        Itmcode_TextEditor.Focus()
                        Exit Sub
                    End If
                End If
            End If



            Me.REQUEST_TabControl.Tabs(1).Enabled = True
            Me.REQUEST_TabControl.Tabs(2).Enabled = True

            Mnual_GroupBox.Visible = False
            Mnual_GroupBox.Enabled = False
            UltraGroupBox1.Enabled = True
            RTTVALU_TextBox.Text = TVAl_Label.Text
            RTTQNTY_TextBox.Text = Val(QTY_Label.Text)

            TARMODE_UltraComboEditor.Enabled = False
            SUPCODE_UltraTextEditor.Enabled = False
            BUNYEAR_TextBox.Enabled = False
            BUNNUMB_TextBox.Enabled = False
            RESCODE_ComboEditor.Enabled = False

            SUPCODE_UltraTextEditor.ReadOnly = True
            RTTVALU_TextBox.ReadOnly = True
            REQSRNO_TextEditor.Focus()

        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub Cancel_Button_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Cancel_Button.Click
        Try

            Me.REQUEST_TabControl.Tabs(1).Enabled = True
            Me.REQUEST_TabControl.Tabs(2).Enabled = True

            Itmcode_TextEditor.Text = ""
            Detail_UltraGrid.DataSource = Nothing
            TVAl_Label.Text = ""
            Mnual_GroupBox.Visible = False
            UltraGroupBox1.Enabled = True
            'TARMODE_UltraComboEditor.DataSource = Nothing
            SUPCODE_UltraTextEditor.Text = ""
            TARMODE_UltraComboEditor.SelectedIndex = 0
            TARMODE_UltraComboEditor.Enabled = True
            BUNYEAR_TextBox.Text = ""
            BUNNUMB_TextBox.Text = ""
            'BUNYEAR_TextBox.Enabled = False
            'BUNNUMB_TextBox.Enabled = False
            TRNSUP_UltraLabel.Visible = False
            TRNSUP_UltraLabel.Text = ""
            'If TARMODE_UltraComboEditor.Value = 5 Then
            '    TARMODE_UltraComboEditor.SelectedIndex = 5
            'Else
            '    TARMODE_UltraComboEditor.SelectedIndex = 1
            'End If
            TARMODE_UltraComboEditor.ReadOnly = False
            TARMODE_UltraComboEditor.Focus()

        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub Detail_UltraGrid_InitializeLayout(ByVal sender As System.Object, ByVal e As Infragistics.Win.UltraWinGrid.InitializeLayoutEventArgs) Handles Detail_UltraGrid.InitializeLayout
        Detail_UltraGrid.DisplayLayout.Override.CellClickAction = CellClickAction.RowSelect
        Detail_UltraGrid.DisplayLayout.Override.SelectTypeRow = SelectType.Single
        Detail_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.FontData.Bold = DefaultableBoolean.True
        Detail_UltraGrid.DisplayLayout.Override.SelectedRowAppearance.ForeColor = Color.Black
        Detail_UltraGrid.DisplayLayout.Override.HeaderAppearance.FontData.SizeInPoints = 9

        e.Layout.Override.HeaderAppearance.ForeColor = Color.White

        Detail_UltraGrid.DisplayLayout.Override.AllowColSizing = AllowColSizing.None
        Detail_UltraGrid.DisplayLayout.Override.AllowColSwapping = AllowColSwapping.NotAllowed
        Detail_UltraGrid.DisplayLayout.Override.AllowColMoving = AllowColMoving.NotAllowed


        e.Layout.Bands(0).Columns("ENTSRNO").Header.Caption = "SR.NO"
        e.Layout.Bands(0).Columns("ENTSRNO").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("ENTSRNO").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("ENTSRNO").Width = 80
        e.Layout.Bands(0).Columns("ENTSRNO").CellActivation = Activation.NoEdit

        e.Layout.Bands(0).Columns("ITMCODE").Header.Caption = "ITEM CODE"
        e.Layout.Bands(0).Columns("ITMCODE").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("ITMCODE").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("ITMCODE").Width = 120
        e.Layout.Bands(0).Columns("ITMCODE").CellActivation = Activation.NoEdit

        e.Layout.Bands(0).Columns("ITMRATE").Header.Caption = "MRP"
        e.Layout.Bands(0).Columns("ITMRATE").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("ITMRATE").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("ITMRATE").Width = 90
        e.Layout.Bands(0).Columns("ITMRATE").CellActivation = Activation.NoEdit

        e.Layout.Bands(0).Columns("qty").Header.Caption = "QNTY "
        e.Layout.Bands(0).Columns("qty").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("qty").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("qty").Width = 120
        e.Layout.Bands(0).Columns("qty").CellActivation = Activation.NoEdit

        e.Layout.Bands(0).Columns("val").Header.Caption = "Total Value "
        e.Layout.Bands(0).Columns("val").CellAppearance.TextHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("val").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("val").Width = 90
        e.Layout.Bands(0).Columns("val").CellActivation = Activation.NoEdit

        e.Layout.Bands(0).Columns("SUPPHTO").Header.Caption = "Image"
        e.Layout.Bands(0).Columns("SUPPHTO").CellAppearance.ImageHAlign = HAlign.Center
        e.Layout.Bands(0).Columns("SUPPHTO").Style = ColumnStyle.Default
        e.Layout.Bands(0).Columns("SUPPHTO").Header.Column.Style = Infragistics.Win.UltraWinGrid.ColumnStyle.Image
        e.Layout.Bands(0).Columns("SUPPHTO").Width = 120
        e.Layout.Bands(0).Columns("SUPPHTO").CellActivation = Activation.AllowEdit


    End Sub

    Private Sub Timer2_Tick(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Timer2.Tick
        If Itmcode_TextEditor.TextLength >= 5 Then
            Timer2.Stop()
            Itmcode_TextEditor.Text = ""
        Else
            Timer2.Stop()
            'MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS soft control")
            Itmcode_TextEditor.Enabled = True
            Itmcode_TextEditor.Text = ""
            Itmcode_TextEditor.Focus()
            Timer1.Stop()
        End If
    End Sub

    Private Sub Itmcode_TextEditor_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles Itmcode_TextEditor.KeyDown
        If Itmcode_TextEditor.TextLength > 0 And Viptime = "" Then
            Viptime = Format(Now, "HH:mm:ss")
        End If
        If e.KeyCode = Keys.ControlKey Then
            MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS soft control")
            Clipboard.SetDataObject("")
            Itmcode_TextEditor.Text = ""
            Itmcode_TextEditor.Tag = 0
            Viptime = ""
            Me.Itmcode_TextEditor.Focus()
        End If
        If e.KeyCode = Keys.Apps Then
            '  MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS Soft control")
            Clipboard.SetDataObject("")
            Itmcode_TextEditor.Text = ""

            Viptime = ""
            Me.Itmcode_TextEditor.Focus()
        End If
        If e.KeyCode = Keys.Back Then
            '   MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS Soft control")
            Clipboard.SetDataObject("")
            Itmcode_TextEditor.Text = ""
            Viptime = ""
            Me.Itmcode_TextEditor.Focus()
        End If
        If e.KeyCode = Keys.Home Then
            'MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS Soft control")
            Clipboard.SetDataObject("")
            Itmcode_TextEditor.Text = ""
            Viptime = ""
            Me.Itmcode_TextEditor.Focus()
        End If

        If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then

            If Itmcode_TextEditor.TextLength > 8 Then
            Else
                MessageBox.Show("There is No Items - Enter the Valid ITEM CODE", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Me.Itmcode_TextEditor.Focus()
                Exit Sub
            End If

            'Dim dvitmscn As New DataView

            If Itmcode_TextEditor.Text.Length < 5 Then
                MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS soft control")
                Itmcode_TextEditor.Clear()
                Itmcode_TextEditor.Focus()
                Exit Sub
            End If

            For Each drvitm In dvitm
                If drvitm("ITMCODE") = Itmcode_TextEditor.Text Then
                    MsgBox("Scan the Item Code Properly", MsgBoxStyle.Critical, "TCS soft control")
                    Itmcode_TextEditor.Text = ""
                    Itmcode_TextEditor.Focus()
                    Exit Sub
                End If
            Next

            Dim dv_pac1 As DataView
            Dim str_pac1 As String
            Dim dv_mode As New DataView
            Dim str_mode As String
            Dim dv_mode1 As New DataView
            Dim str_mode1 As String
            '           str_mode = " select distinct nvl((select distinct Paccode from pac_despatch_prd where prdcode=prd.prdcode),0) paccode,(trunc(sysdate) - trunc(pur.adddate)) DIFFDAYS,ITM.SUPCODE" & _
            '" from itm_mas itm,packing_slip pac,pur_det pur,PRODUCT PRD " & _
            '" where itm.prdcode=prd.prdcode and itm.bunyear=pac.pacyear and itm.bunnumb=pac.pacnumb and pac.puryear=pur.PURYEAR and pac.purcono=pur.PURCONO and itm.itmcode='" & Itmcode_TextEditor.Text & " and '"
            '           dv_mode = TCS_Lib.Get_Centra_Cmd_View(str_mode)
            '           Dim dv_pac As New DataView
            '           Dim str_pac As String
            '           str_pac = " select distinct nvl((select distinct Paccode from pac_despatch_prd where prdcode=prd.prdcode),0) paccode,(trunc(sysdate) - trunc(pur.adddate)) DIFFDAYS,ITM.SUPCODE" & _
            '" from itm_mas itm,packing_slip pac,pur_det pur,PRODUCT PRD " & _
            '" where itm.prdcode=prd.prdcode and itm.bunyear=pac.pacyear and itm.bunnumb=pac.pacnumb and pac.puryear=pur.PURYEAR and pac.purcono=pur.PURCONO and itm.itmcode='" & Itmcode_TextEditor.Text & "'"
            '           dv_pac = TCS_Lib.Get_Cmd_View(str_pac)

            Dim dv_lock As New DataView
            dv_lock = TCS_Lib.Get_Cmd_View("SELECT * FROM BUNDLE_UPDATE_LOCK WHERE LCKCODE=503 and BRNCODE=" & Branch_UltraComboEditor.Value & "")

            If dv_lock.Count > 0 Then
                If dv_lock(0)("BUNLOCK") = "Y" Then
                    str_pac1 = "select distinct itm.itmusrd,itm.itmcode " & _
                   "from itm_mas itm " & _
                    "where  itm.itmcode='" & Itmcode_TextEditor.Text & "' and itm.itmsqty+itm.itmgqty>0 and itm.brncode=" & Branch_UltraComboEditor.Value & " and  ((trunc(sysdate) - trunc(itm.ITMUSRD)) > " & Val(dv_lock(0)("LCKDAYS")) & ") "
                    dv_pac1 = TCS_Lib.Get_Cmd_View(str_pac1)

                    str_mode1 = "select distinct mod.Paccode,mod.pacmode,prd.paccode,prd.prdcode,itm.itmcode,itm.prdcode ,itm.bunyear " & _
                        "from pac_despatch_mode mod, pac_despatch_prd prd, itm_mas itm " & _
                        " where itm.prdcode=prd.prdcode and prd.paccode=mod.paccode and mod.paccode=2 and itm.itmsqty+itm.itmgqty>0 and itm.brncode=" & Branch_UltraComboEditor.Value & "  and  itm.itmcode='" & Itmcode_TextEditor.Text & "'"
                    dv_mode1 = TCS_Lib.Get_Cmd_View(str_mode1)

                    If dv_mode1.Count <> 0 And dv_pac1.Count <> 0 Then
                        MessageBox.Show("This is the Mix Lot  Item and Above " & Val(dv_lock(0)("LCKDAYS")) & " Days So Item Debit Not Possible.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Itmcode_TextEditor.Focus()
                        Exit Sub
                    ElseIf dv_mode1.Count <> 0 Then
                        MessageBox.Show("This is the Mix Lot Item Debit Not Possible ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Itmcode_TextEditor.Focus()
                        Exit Sub
                    ElseIf dv_pac1.Count <> 0 Then
                        MessageBox.Show("Above " & Val(dv_lock(0)("LCKDAYS")) & " Days Item Debit Not Possible", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        Itmcode_TextEditor.Focus()
                        Exit Sub
                    End If
                End If
               
            Else
                MessageBox.Show("Contact It Department Debit Entry Made Not Possible", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Itmcode_TextEditor.Focus()
                Exit Sub
            End If

            

            Dim dv As DataView
            dv = New DataView

            Dim dv1 As DataView
            dv1 = New DataView
            dv1 = TCS_Lib.Get_Cmd_View("select * from bundle_update_lock where lckcode=16 AND BRNCODE=" & Branch_UltraComboEditor.Value & "")

            If dv1.Count > 0 Then
                If dv1(0).Item("BUNLOCK") = "Y" Then
                    If Detail_UltraGrid.Rows.Count = 0 And RESCODE_ComboEditor.Value = 29 Then

                        dv_SGroup = New DataView
                        dv_SGroup = TCS_Lib.Get_Cmd_View("select SGRCODE from supplier where deleted='N' and supcode=" & SUPCODE_UltraTextEditor.Tag & "")

                        If dv_SGroup.Count = 0 Then
                            MessageBox.Show("There is No Detail Found ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            Itmcode_TextEditor.Focus()
                            Exit Sub
                        End If

                        Dim dvitmscn1 As DataView
                        dvitmscn1 = New DataView
                        dvitmscn1 = TCS_Lib.Get_Cmd_View("select grp.secgrno,itm.ITMCODE,itm.ITMRATE,itm.prdcode,sum(itm.Itmsqty+itm.Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm,product prd,section_group_report grp where itm.prdcode=prd.prdcode and prd.seccode=grp.seccode and (Itmsqty+Itmgqty)>0 and brncode=" & Branch_UltraComboEditor.Value & " and supcode in (select SUPCODE from supplier where SGRCODE=" & dv_SGroup(0)("SGRCODE") & ") and itmcode='" & Itmcode_TextEditor.Text & "' group by itm.ITMCODE,itm.ITMRATE,itm.prdcode,GRP.secgrno")

                        dv = TCS_Lib.Get_Cmd_View("Select distinct(sec.secgrno) seccode,sec.secname, tar.brncode,substr(brn.nicname,3,10) brnname,tar.taryear,tar.tarnumb, " & _
                        "tar.mrtyear,tar.mrtnumb,tar.poryear,tar.pornumb,tar.puryear,  tar.purcono,tar.rtconv,tar.tarvalu tarval,trunc(msum.adddate) from " & _
                        "trandata.Return_target@tcscentr ret,trandata.target_request_approval@tcscentr App,trandata.supplier_po_target@tcscentr tar, " & _
                        "trandata.manual_return_slip_summary@tcscentr msum,trandata.section_group_report@tcscentr Sec,branch brn where ret.deleted='N'  And Ret.BrnCode=App.BrnCode And Ret.RttYear=App.TarYear  " & _
                        "And Ret.RttNumb=App.TarNumb and App.Deleted='N' and App.ResCode=29 and App.TarNumb>0 and ret.brncode=tar.brncode(+) and ret.retyear=tar.mrtyear(+) and ret.retnumb= tar.mrtnumb(+)  " & _
                        "and msum.brncode=tar.brncode and msum.mrtyear=tar.mrtyear and msum.mrtnumb=tar.mrtnumb and ret.brncode=" & Branch_UltraComboEditor.Value & " and msum.deleted='N' and trunc(msum.adddate)<=trunc(SYSDATE)-8 " & _
                        "and NVL(tar.RTCONV,'N')='N' and sec.secgrno=" & dvitmscn1(0).Item("secgrno") & " and ret.SecCode=Sec.SecCode and tar.brncode=brn.brncode order by taryear,tarnumb,poryear,pornumb,puryear,purcono")

                        If dv.Count > 0 Then
                            MessageBox.Show("Not Convert WOS Exchange Debit", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            Exit Sub
                        End If
                    End If
                End If
            End If

            If TARMODE_UltraComboEditor.Value = 5 Then

                'dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm  where (Itmsqty+Itmgqty)>0 and brncode=" & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)) & " and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                If Super_bazar_CheckBox.Checked = True Then
                    dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from sb_itm_mas itm  where (Itmsqty+Itmgqty)>0 and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                Else
                    dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm  where (Itmsqty+Itmgqty)>0 and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                End If

            Else

                'dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm  where (Itmsqty+Itmgqty)>0 and brncode=" & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)) & " and supcode=" & SUPCODE_UltraTextEditor.Tag & " and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                dv_SGroup = New DataView
                dv_SGroup = TCS_Lib.Get_Cmd_View("select SGRCODE from supplier where deleted='N' and supcode=" & SUPCODE_UltraTextEditor.Tag & "")

                If dv_SGroup.Count = 0 Then
                    MessageBox.Show("There is No Detai Found ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    Itmcode_TextEditor.Focus()
                    Exit Sub
                End If
                If Super_bazar_CheckBox.Checked = True Then
                    dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from sb_itm_mas itm  where (Itmsqty+Itmgqty)>0 and brncode=" & Branch_UltraComboEditor.Value & " and supcode in (select SUPCODE from supplier where SGRCODE=" & dv_SGroup(0)("SGRCODE") & ") and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                Else
                    dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm  where (Itmsqty+Itmgqty)>0 and brncode=" & Branch_UltraComboEditor.Value & " and supcode in (select SUPCODE from supplier where SGRCODE=" & dv_SGroup(0)("SGRCODE") & ") and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode ")
                End If


            End If
            'dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE from itm_mas where (Itmsqty+Itmgqty)>0 and brncode=" & IIf(TCS_Lib.Get_Emp_Brncode(MainForm.UserID) = 100, 888, TCS_Lib.Get_Emp_Brncode(MainForm.UserID)) & " and supcode=" & SUPCODE_UltraTextEditor.Tag & " and itmcode='" & Itmcode_TextEditor.Text & "'  ")
            'dvitmscn = TCS_Lib.Get_Cmd_View("select ITMCODE,ITMRATE,prdcode,sum(Itmsqty+Itmgqty) qty,sum((itm.itmsqty+itm.itmgqty)*itm.itmrate) val from itm_mas itm  where (Itmsqty+Itmgqty)>0  and itmcode='" & Itmcode_TextEditor.Text & "' group by ITMCODE,ITMRATE,prdcode")
            If dvitmscn.Count = 0 Then
                MessageBox.Show("There is No Detai Found ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                Itmcode_TextEditor.Clear()
                Itmcode_TextEditor.Focus()
                Exit Sub
            Else
                Dim dv_prd As New DataView
                dv_prd = TCS_Lib.Get_Cmd_View("select UNTCODE from product where prdcode='" & dvitmscn(0)("prdcode") & "'")
                ImgSelect = False

                itm_photo_add()

                If ImgSelect = True Then
                    If dv_prd(0)("UNTCODE") = "2" And mtr_item1 = False Or dv_prd(0)("UNTCODE") = "7" And Super_bazar_CheckBox.Checked = True Then
                        Timer2.Stop()
                        mtr_Label.Enabled = True
                        mtr_Label.Visible = True
                        Mtr_TextBox.Visible = True
                        Mtr_TextBox.Enabled = True
                        Itmcode_TextEditor.Text = dvitmscn(0)("ITMCODE")
                        Mtr_TextBox.Text = ""
                        UltraLabel20.Text = ""
                        If dv_prd(0)("UNTCODE") = "7" And Super_bazar_CheckBox.Checked = True Then
                            UltraLabel20.Text = "KG:"
                        Else
                            UltraLabel20.Text = "TOTAL MCM:"
                        End If
                        mtr_item1 = False
                        Mtr_item = True
                        Mtr_TextBox.Focus()
                    Else
                        If dv_prd(0)("UNTCODE") <> "2" And Mtr_item = False Then
                            dvitm.AllowNew = True
                            drvitm = dvitm.AddNew
                            drvitm.BeginEdit()
                            drvitm("ENTSRNO") = dvitm.Count
                            drvitm("ITMCODE") = dvitmscn(0)("ITMCODE")
                            drvitm("ITMRATE") = dvitmscn(0)("ITMRATE")
                            drvitm("qty") = dvitmscn(0)("qty")
                            drvitm("val") = dvitmscn(0)("val")
                            Dim bmp As Bitmap
                            'dvexp.AllowNew = True
                            bmp = New Bitmap(Me.PictureBox1.Image, New Size(800 * 1, 600 * 1))
                            Dim Img As Byte()
                            Dim ms As New IO.MemoryStream
                            ReDim Img(ms.Length)
                            bmp.Save(ms, Imaging.ImageFormat.Jpeg)
                            ms.Close()
                            Img = ms.ToArray
                            drvitm("SUPPHTO") = Img
                            drvitm.EndEdit()
                            dvitm.AllowNew = False
                            Detail_UltraGrid.DataSource = dvitm

                            TVAl_Label.Text = Val(TVAl_Label.Text) + dvitmscn(0)("val")
                            Itmcode_TextEditor.Text = ""
                            UltraLabel20.Text = ""
                            UltraLabel20.Text = "QNTY:"
                            Itmcode_TextEditor.Focus()
                            QTY_Label.Text = dvitm.Table.Compute("Max(ENTSRNO)", "")
                            mtr_item1 = True
                        Else
                            MessageBox.Show("Enter The corect Item code  ", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                            Itmcode_TextEditor.Clear()
                            Itmcode_TextEditor.Focus()
                            Exit Sub
                        End If
                    End If
                End If

            End If
        End If
    End Sub

    Private Sub Itmcode_TextEditor_KeyUp(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles Itmcode_TextEditor.KeyUp
        If Itmcode_TextEditor.Text.Length > 0 Then
            Timer2.Start()
            Timer2.Interval = 100
        End If
    End Sub

    Private Sub Itmcode_TextEditor_MouseDown(ByVal sender As Object, ByVal e As System.Windows.Forms.MouseEventArgs) Handles Itmcode_TextEditor.MouseDown
        If MainForm.UserID = 1426001 Or MainForm.UserID = 1986888 Or MainForm.UserID = 1139001 Or MainForm.UserID = 1449888 Then
        Else
            If e.Button = Windows.Forms.MouseButtons.Right Then
                Clipboard.SetDataObject("")
                Itmcode_TextEditor.Text = ""
                Me.Itmcode_TextEditor.Text = ""
                Viptime = ""
                Me.Itmcode_TextEditor.Focus()
            End If


        End If
    End Sub

    Private Sub Mtr_TextBox_KeyDown(ByVal sender As Object, ByVal e As System.Windows.Forms.KeyEventArgs) Handles Mtr_TextBox.KeyDown
        Try
            If e.KeyCode = Keys.Enter Or e.KeyCode = Keys.Tab Then
                If Mtr_TextBox.Text.Length = 0 Then
                    MsgBox("Enter the product Mtr  ", MsgBoxStyle.Critical, "TCS soft control")
                    Mtr_TextBox.Clear()
                    Mtr_TextBox.Focus()
                    Exit Sub
                Else
                    If Val(Mtr_TextBox.Text) > dvitmscn(0)("qty") Then
                        MsgBox("You Have Entr only " & dvitmscn(0)("qty") & " qty  ", MsgBoxStyle.Critical, "TCS soft control")
                        Mtr_TextBox.Clear()
                        Mtr_TextBox.Focus()
                        Exit Sub
                    End If
                    Dim mtr_Vale As Decimal
                    mtr_Vale = 0
                    mtr_Vale = Val(Mtr_TextBox.Text) * dvitmscn(0)("ITMRATE")

                    dvitm.AllowNew = True
                    drvitm = dvitm.AddNew
                    drvitm.BeginEdit()
                    drvitm("ENTSRNO") = dvitm.Count
                    drvitm("ITMCODE") = dvitmscn(0)("ITMCODE")
                    drvitm("ITMRATE") = dvitmscn(0)("ITMRATE")
                    drvitm("qty") = Mtr_TextBox.Text
                    drvitm("val") = mtr_Vale
                    Dim bmp As Bitmap
                    'dvexp.AllowNew = True
                    bmp = New Bitmap(Me.PictureBox1.Image, New Size(800 * 1, 600 * 1))
                    Dim Img As Byte()
                    Dim ms As New IO.MemoryStream
                    ReDim Img(ms.Length)
                    bmp.Save(ms, Imaging.ImageFormat.Jpeg)
                    ms.Close()
                    Img = ms.ToArray
                    drvitm("SUPPHTO") = Img
                    drvitm.EndEdit()
                    dvitm.AllowNew = False
                    Detail_UltraGrid.DataSource = dvitm
                    TVAl_Label.Text = Val(TVAl_Label.Text) + mtr_Vale
                    QTY_Label.Text = Val(QTY_Label.Text) + Val(Mtr_TextBox.Text)
                    Mtr_TextBox.Text = ""
                    Itmcode_TextEditor.Text = ""
                    Mtr_TextBox.Enabled = False
                    Itmcode_TextEditor.Focus()

                End If
            End If
        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Private Sub RESCODE_ComboEditor_TabStopChanged(ByVal sender As Object, ByVal e As System.EventArgs) Handles RESCODE_ComboEditor.TabStopChanged

    End Sub

    Private Sub RESCODE_ComboEditor_TextChanged(ByVal sender As Object, ByVal e As System.EventArgs) Handles RESCODE_ComboEditor.TextChanged
        If LMode <> "FIND" Then

            If TARMODE_UltraComboEditor.Value = 5 Then
                If RESCODE_ComboEditor.Value = 30 Then
                    WOS_CheckBox.Checked = False
                    RESCODE_ComboEditor.Enabled = False
                    ITMCODE_ENTER()
                    'Else
                    '    MessageBox.Show("Selected Reason Not Entered For Transport Debit Mode", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                    '    RESCODE_ComboEditor.Focus()
                    '    Exit Sub
                End If
            Else
                If TARMODE_UltraComboEditor.Value > 0 Then
                    If RESCODE_ComboEditor.Value > 0 Then
                        If RESCODE_ComboEditor.Value <> 29 Then
                            WOS_CheckBox.Enabled = False
                            Invoice_Load()
                        Else
                            WOS_CheckBox.Enabled = True
                            If TARMODE_UltraComboEditor.Value = 2 Then
                                WOS_CheckBox.Checked = True
                                RESCODE_ComboEditor.Enabled = False
                                ITMCODE_ENTER()
                            End If
                        End If
                    End If
                End If
            End If
        End If
    End Sub


    Private Sub itm_photo_add()
        ''  If Res_Combo.Value = 8 Or Res_Combo.Value = 15 Then
        Dim dvpr As New DataView
        Dim str As String = ""

        Dim Itemcodewise_TPPhoto_Form As New Itemcodewise_TPPhoto_Form
        Itemcodewise_TPPhoto_Form.IMAGE_PictureBox.Visible = False
        Itemcodewise_TPPhoto_Form.VIDEO_PictureBox.Visible = True
        Itemcodewise_TPPhoto_Form.ITM_CODE_Label.Visible = False
        ''Itemcodewise_TPPhoto_Form.FrmName = "ITEM PHOTOS"
        ''Itemcodewise_TPPhoto_Form.Text = "Sample Images"
        Itemcodewise_TPPhoto_Form.ITM_CODE_Label.Text = "Item Code : " & Me.Itmcode_TextEditor.Text.Trim
        Itemcodewise_TPPhoto_Form.ITMCODE = Me.Itmcode_TextEditor.Text.Trim
        Itemcodewise_TPPhoto_Form.MRP = dvitmscn(0)("ITMRATE")
        Itemcodewise_TPPhoto_Form.ShowDialog()
        If Itemcodewise_TPPhoto_Form.SELECT_OK = True Then
            Dim bmp As Bitmap
            bmp = New Bitmap(Itemcodewise_TPPhoto_Form.IMAGE_PictureBox.Image, New Size(600 * 0.4, 400 * 0.4))
            Dim ms As New IO.MemoryStream
            ReDim img(ms.Length)
            bmp.Save(ms, Imaging.ImageFormat.Jpeg)
            ms.Close()
            img = ms.ToArray
            With PictureBox1
                .Image = bmp
            End With
            ImgSelect = True
            Itemcodewise_TPPhoto_Form.SELECT_OK = False
            Itemcodewise_TPPhoto_Form.IMAGE_PictureBox.Image = Nothing
        Else
            MessageBox.Show("Item Photo Not Valid", "TCS Soft Control", MessageBoxButtons.OK, MessageBoxIcon.Information)
            ImgSelect = False
            Exit Sub
        End If

    End Sub

    Private Sub MakeDir(ByVal dirName As String)


        Dim reqFTP As FtpWebRequest = Nothing
        Dim ftpStream As Stream = Nothing
        Try
            reqFTP = DirectCast(FtpWebRequest.Create(New Uri(Appl_path + dirName)), FtpWebRequest)
            reqFTP.Method = WebRequestMethods.Ftp.MakeDirectory
            reqFTP.UseBinary = True
            reqFTP.Credentials = New NetworkCredential("ituser", "S0ft@369")
            Dim response As FtpWebResponse = DirectCast(reqFTP.GetResponse(), FtpWebResponse)
            ftpStream = response.GetResponseStream()
            ftpStream.Close()
            response.Close()
        Catch ex As Exception
            If ftpStream IsNot Nothing Then
                ftpStream.Close()
                ftpStream.Dispose()
            End If
            ''Throw New Exception(ex.Message.ToString())
        End Try
    End Sub
    Private Sub SECCODE_ComboEditor_ValueChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles SECCODE_ComboEditor.ValueChanged

        Try

            Dim PmDv As DataView
            Dim Str As String
            TOEMPSRNO_TextEditor.Text = Nothing
            TODETL_UltraLabel.Text = Nothing
            Str = "select emp.empsrno,(emp.empcode||'-'||emp.empname) as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode  and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & " union select emp.empsrno,emp.empcode||'-'||emp.empname as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and des.deleted='N' and brn.deleted='N' and sec.deleted='N' and sec.seccode=" & SECCODE_ComboEditor.Value & ""

            If Super_bazar_CheckBox.Checked = True Then
                dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn, employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and  emp.empcode=5069 ")
            Else

                If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Or TARMODE_UltraComboEditor.Value = 6 Then

                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & "  union select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and emp.brncode=888 and emp.empcode>1000 and  sec.seccode=" & SECCODE_ComboEditor.Value & " ")
                ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                    If TOBRNCODE_ComboBox.SelectedValue = 104 Or TOBRNCODE_ComboBox.SelectedValue = 112 Or TOBRNCODE_ComboBox.SelectedValue = 114 Or TOBRNCODE_ComboBox.SelectedValue = 116 Then
                        EmpStr = " and emp.empcode=5069 and brn.brncode=888"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 102 Then
                        'EmpStr = " and emp.empcode=2358 and brn.brncode=888"
                        EmpStr = " and emp.empcode=12370 and brn.brncode=102"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 888 Or TOBRNCODE_ComboBox.SelectedValue = 100 Or TOBRNCODE_ComboBox.SelectedValue = 103 Or TOBRNCODE_ComboBox.SelectedValue = 201 Then
                        'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                        EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 21 Then
                        'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                        EmpStr = " and des.descode in (92,96,3,75)  and brn.brncode=16 and emp.empsrno  in (select empsrno from employee_salary where paycompany=1)"
                    Else
                        EmpStr = " and des.descode in (92,96,3,75)  and emp.empsrno in (select empsrno from employee_salary where paycompany=1) and  brn.brncode=" & TOBRNCODE_ComboBox.SelectedValue & ""
                    End If
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & " ")
                ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                    EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                Else
                    EmpStr = " and emp.empsrno in (1050,28190)"
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                End If
            End If
           

            ' PmDv = TCS_Lib.Get_Cmd_View(Str)
            PMname_UltraCombo.DataSource = dv
            PMname_UltraCombo.DisplayMember = "empjoin"
            PMname_UltraCombo.ValueMember = "Empsrno"

        Catch ex As Exception

        End Try

    End Sub

    Private Sub PMname_UltraCombo_SelectedValueChanged(ByVal sender As Object, ByVal e As System.EventArgs) Handles PMname_UltraCombo.SelectedValueChanged

        Try

            If IsNothing(PMname_UltraCombo.SelectedValue) = False Then


                If PMname_UltraCombo.SelectedValue.ToString <> "System.Data.DataRowView" Then
                    TODETL_UltraLabel.Text = Nothing
                    TOEMPSRNO_TextEditor.Text = Nothing
                    dv = New DataView
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and ese.deleted='N' and des.deleted='N' and brn.deleted='N' and emp.empcode>1000 and emp.empsrno=" & PMname_UltraCombo.SelectedValue & "")
                    If dv.Count > 0 Then

                        TOEMPSRNO_TextEditor.Text = dv(0).Item("EMPCODE") & "-" & dv(0).Item("EMPNAME")
                        TOEMPSRNO_TextEditor.Tag = dv(0).Item("EMPSRNO")

                        TODETL_UltraLabel.Text = "Branch: " & dv(0).Item("BRNNAME") & vbCrLf & vbCrLf & "Section: " & dv(0).Item("ESENAME") & vbCrLf & vbCrLf & "Designation: " & dv(0).Item("DESNAME")
                        TODETL_UltraLabel.Tag = dv(0).Item("DESSRNO")

                        toecode = dv(0).Item("ESECODE")
                        todcode = dv(0).Item("DESCODE")
                    Else
                        MessageBox.Show("Enter Valid Employee Ec.No.", "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Information)
                        TOEMPSRNO_TextEditor.Clear()
                        TOEMPSRNO_TextEditor.Focus()
                        TODETL_UltraLabel.Text = Nothing
                        Exit Sub
                    End If
                Else
                    TOEMPSRNO_TextEditor.Clear()
                    TODETL_UltraLabel.Text = Nothing
                End If
            Else
                TOEMPSRNO_TextEditor.Clear()
                TODETL_UltraLabel.Text = Nothing
            End If

        Catch ex As Exception
            MessageBox.Show(ex.Message, "TCS Centra", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try


    End Sub

    Private Sub Branch_UltraComboEditor_ValueChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Branch_UltraComboEditor.ValueChanged
        Try
            If Branch_UltraComboEditor.Value = 14 Then
                Super_bazar_CheckBox.Visible = True
                Super_bazar_CheckBox.Checked = False

            Else
                Super_bazar_CheckBox.Checked = False
                Super_bazar_CheckBox.Visible = False
            End If
        Catch ex As Exception

        End Try
    End Sub

    Private Sub Super_bazar_CheckBox_CheckedChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Super_bazar_CheckBox.CheckedChanged
        Try
            If Super_bazar_CheckBox.Checked = True Then

                Dim PmDv As DataView
                Dim Str As String
                TOEMPSRNO_TextEditor.Text = Nothing
                TODETL_UltraLabel.Text = Nothing
                Str = "select emp.empsrno,(emp.empcode||'-'||emp.empname) as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode  and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & " union select emp.empsrno,emp.empcode||'-'||emp.empname as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and des.deleted='N' and brn.deleted='N' and sec.deleted='N' and sec.seccode=" & SECCODE_ComboEditor.Value & ""

                If Super_bazar_CheckBox.Checked = True Then
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn, employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and  emp.empcode=5069 ")
                Else

                    If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then

                        dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & "  union select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and emp.empcode>1000 and  sec.seccode=" & SECCODE_ComboEditor.Value & " ")
                    ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                        If TOBRNCODE_ComboBox.SelectedValue = 104 Or TOBRNCODE_ComboBox.SelectedValue = 112 Or TOBRNCODE_ComboBox.SelectedValue = 114 Or TOBRNCODE_ComboBox.SelectedValue = 116 Then
                            EmpStr = " and emp.empcode=5069 and brn.brncode=888"
                        ElseIf TOBRNCODE_ComboBox.SelectedValue = 102 Then
                            'EmpStr = " and emp.empcode=2358 and brn.brncode=888"
                            EmpStr = " and emp.empcode=12370 and brn.brncode=102"
                        ElseIf TOBRNCODE_ComboBox.SelectedValue = 888 Or TOBRNCODE_ComboBox.SelectedValue = 100 Or TOBRNCODE_ComboBox.SelectedValue = 103 Or TOBRNCODE_ComboBox.SelectedValue = 201 Then
                            'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                            EmpStr = " and emp.empsrno in (1050,2231,28190)"
                        ElseIf TOBRNCODE_ComboBox.SelectedValue = 21 Then
                            'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                            EmpStr = " and des.descode in (92,96,3,75)  and brn.brncode=16 and emp.empsrno  in (select empsrno from employee_salary where paycompany=1)"
                        Else
                            EmpStr = " and des.descode in (92,96,3,75)  and emp.empsrno in (select empsrno from employee_salary where paycompany=1) and  brn.brncode=" & TOBRNCODE_ComboBox.SelectedValue & ""
                        End If
                        dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & " ")
                    ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                        EmpStr = " and emp.empsrno in (1050,2231,28190)"
                        dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                    Else
                        EmpStr = " and emp.empsrno in (1050,28190)"
                        dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                    End If
                End If


                ' PmDv = TCS_Lib.Get_Cmd_View(Str)
                PMname_UltraCombo.DataSource = dv
                PMname_UltraCombo.DisplayMember = "empjoin"
                PMname_UltraCombo.ValueMember = "Empsrno"
            ElseIf Super_bazar_CheckBox.Checked = False Then

                Dim PmDv As DataView
                Dim Str As String
                TOEMPSRNO_TextEditor.Text = Nothing
                TODETL_UltraLabel.Text = Nothing
                Str = "select emp.empsrno,(emp.empcode||'-'||emp.empname) as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode  and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & " union select emp.empsrno,emp.empcode||'-'||emp.empname as empjoin from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and des.deleted='N' and brn.deleted='N' and sec.deleted='N' and sec.seccode=" & SECCODE_ComboEditor.Value & ""

                If TARMODE_UltraComboEditor.Value = 1 Or TARMODE_UltraComboEditor.Value = 2 Then

                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_group_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,132) and sec.seccode=" & SECCODE_ComboEditor.Value & "  union select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn,pur_head_section grp,section sec where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.descode=des.descode and emp.empsrno=grp.empsrno and sec.seccode=grp.seccode and grp.empsrno is not null and des.descode in(136,138,149,151,153,75,3,133,132) and emp.empcode>1000 and  sec.seccode=" & SECCODE_ComboEditor.Value & " ")
                ElseIf TARMODE_UltraComboEditor.Value = 3 Then
                    If TOBRNCODE_ComboBox.SelectedValue = 104 Or TOBRNCODE_ComboBox.SelectedValue = 112 Or TOBRNCODE_ComboBox.SelectedValue = 114 Or TOBRNCODE_ComboBox.SelectedValue = 116 Then
                        EmpStr = " and emp.empcode=5069 and brn.brncode=888"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 102 Then
                        'EmpStr = " and emp.empcode=2358 and brn.brncode=888"
                        EmpStr = " and emp.empcode=12370 and brn.brncode=102"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 888 Or TOBRNCODE_ComboBox.SelectedValue = 100 Or TOBRNCODE_ComboBox.SelectedValue = 103 Or TOBRNCODE_ComboBox.SelectedValue = 201 Then
                        'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                        EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    ElseIf TOBRNCODE_ComboBox.SelectedValue = 21 Then
                        'EmpStr = " and emp.empcode in (2442,5343) and brn.brncode=888"
                        EmpStr = " and des.descode in (92,96,3,75)  and brn.brncode=16 and emp.empsrno  in (select empsrno from employee_salary where paycompany=1)"
                    Else
                        EmpStr = " and des.descode in (92,96,3,75)  and emp.empsrno in (select empsrno from employee_salary where paycompany=1) and  brn.brncode=" & TOBRNCODE_ComboBox.SelectedValue & ""
                    End If
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & " ")
                ElseIf TARMODE_UltraComboEditor.Value = 5 Then
                    EmpStr = " and emp.empsrno in (1050,2231,28190)"
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                Else
                    EmpStr = " and emp.empsrno in (1050,28190)"
                    dv = TCS_Lib.Get_Cmd_View("select brn.brnname,(emp.empcode||'-'||emp.empname) as empjoin,abrn.attnbrn brncode,emp.empsrno,emp.empcode,emp.empname,des.descode,des.dessrno,des.desname,ese.esecode,ese.esename from employee_office emp,empsection ese,designation des,branch brn,employee_attn_branch abrn where emp.empsrno=abrn.empsrno and emp.brncode=brn.brncode and emp.esecode=ese.esecode and emp.empcode>1000 and  emp.descode=des.descode " & EmpStr & "")
                End If


                ' PmDv = TCS_Lib.Get_Cmd_View(Str)
                PMname_UltraCombo.DataSource = dv
                PMname_UltraCombo.DisplayMember = "empjoin"
                PMname_UltraCombo.ValueMember = "Empsrno"

            End If
        Catch ex As Exception

        End Try
    End Sub
 
End Class

