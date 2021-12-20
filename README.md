![iScholarlogo](https://github.com/SistemaiScholar/moodle-auth_ischolar/blob/main/docs/logo1.png)
# iScholar <> Moodle Synchronization 

The `iScholar <> Moodle Synchronization` integration aims to easily, quickly and conveniently send data from your iScholar to Moodle.
This manual will guide you about the data sent to Moodle and through the step-by-step activation, deactivation and settings procedures for your iScholar system. 

## Data sent to Moodle

Teachers' personal data: ID code, first name, surname, nickname, email, address, telephone numbers, admission date, graduation, specialization, master's, doctorate, field of activity, area of interest and research projects. 

Students' personal data: ID code, first name, surname, social name, email, address, telephone number and birth date. 

Subjects: start and end dates and times. For teachers the dates are based on the timetable. For students, the dates are calculated taking into account the enrollment, the school term and the dates informed in the mapping of subjects with Moodle courses in the curriculum. 

Other information: year of school term, course name and modality, and class name. These data make up the student group names created in Moodle. Thus, within the same course in Moodle, students will be divided into groups according to the class they belong to in iScholar. 

The subject-related data is used to enroll and/or unenroll a teacher or student in a Moodle course that is mapped in iScholar. 

Only data from professors and students linked to subjects mapped with Moodle courses and who have a registered email address are sent and those who do not have a registration in Moodle will be registered automatically, while those who already have it will have their registration updated. The same applies to enrollment in courses for teachers and students. Therefore, keep in mind that changing these data directly in Moodle may result in loss of information triggered by some later data synchronization. 

In the case of synchronizing data from teachers and/or students who already have a registration in Moodle (ie who were registered manually before activating the integration), the synchronization process will compare the registrations through the email address, therefore, it is necessary that teachers and students have the same email address registered in both Moodle and iScholar. 

## Situations where data is sent to Moodle

Data is sent to Moodle via manual and automatic syncs. 

The manual synchronization can be accessed as follows (the screenshots are in portuguese since the iScholar is available only in that language): 

* Go to the `Administração` » `Integrações` menu.<br/>
![img-01](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/01.png)
 
* Select the `Moodle sincronização` option.<br/>
![img-02](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/02.png)

* A window will open. Click the arrow on the integration menu then click on `Sincronização de dados` option.<br/>
![img-03](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/03.png)

* Click the `Professores ativos` or `Alunos ativos` buttons (in the section `Sincronização manual`) to execute the manual synchronization.<br/>
![img-04](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/04.png)

If enabled, the automatic synchronization (section `Sincronização automática`) runs automatically when performing miscellaneous tasks in iScholar. To enable or disable it, access the integration synchronization page through the steps specified in the previous paragraph.<br/>
![img-05](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/05.png)

Once activated, a automatic synchronization will be performed in the following situations:

* Changing the teacher's personal data.
* Changing the student's personal data.
* Enroll, unenroll or change the enrollment of a student.
* Relocation of students.
* Registration, modification and removal of a subject in the curriculum.
* Changing dates for a school term.
* Creating, changing and removing timetables.
* Registration and removal of teachers from timetables.
* Changing a class's curriculum.
* Enrollment and removal from enrollment in optional subjects.

When performing each of the tasks listed above, the automatic synchronization will check whether or not you need to send information to Moodle and, if so, send it. 

Note that automatic synchronization is performed asynchronously. Therefore, the effect of it in Moodle can, in some cases, take a few minutes, depending on certain factors, such as your internet speed, the response time of the server where Moodle is installed, the amount of data to be processed, sent or received from Moodle, the number of other asynchronous tasks already running, etc. 

## Time zone

As we saw earlier, your iScholar system will send Moodle date and time information about the teacher and student enrollments. In order to maintain the consistency of these data in both systems it is necessary that both iScholar and Moodle use the same time zone. 

Since your iScholar is automatically set to the appropriate time zone for the various financial and administrative tasks, we recommend changing, if necessary, only Moodle's time zones. 

In Moodle there are two timezone settings: the system default zone and the logged-in user zone. To change Moodle's default timezone follow these steps: 

* Log in to Moodle as administrator. Click on `Site administration` menu then click on `Site administration` tab.<br/>
![img-06](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/06.png)

* Search for the `Location` section then click on `Location settings` option.<br/>
![img-07](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/07.png)

* Select the appropriate time zone option for your region then click on `Save changes` at the page end.<br/>
![img-08](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/08.png)

Follow the next steps to change the logged user's timezone:

* Click the arrow next to the user's image in the window upper-right corner and select the `Profile` option.<br/>
![img-09](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/09.png)
 
* In the user profile page, click on the `Edit profile` link.<br/>
![img-10](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/10.png)

* In the user's general settings, look for the `Timezone` option and change it to the appropriate time zone for your region then click the `Update profile` button at the bottom of the page.<br/>
![img-11](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/11.png)

If you don't know which time zone is most appropriate, go to the time zone settings page in your iScholar to see which option is currently in use. Proceed as follows to access the time zone settings in your iScholar:

* Go to  `Administração` » `Configurações` menu.<br/>
![img-12](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/12.png)

* Click on the `Fuso horário` button.<br/>
![img-13](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/13.png)

* Check the time zone option currently in use and, if necessary, select the most appropriate option for your region then click on the `Salvar` button.<br/>
![img-14](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/14.png)

## Activating the integration

Before you start, make sure you meet the following requirements:

* You must have the Moodle installed on a server accessible from the Internet. Must be version 2.5 or later. Version 3.11 is recommended. 
* You must have administrative access to the Moodle site referred to in the previous item.
* You must have access to an iScholar system and permission to manage tokens and to change the time zone setting.

First you will need to install the `iScholar <> Moodle Synchronization` plugin on your Moodle site proceeding as follows:

* Download the `iScholar <> Moodle Synchronization` plugin by clicking [here](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/raw/main/dist/tool_ischolarsync-latest.zip). Save the file to an easily accessible folder on your computer. You may also search for and download the plugin through the Moodle plugins directory through the link <https://moodle.org/plugins/index.php>.

* Go to your Moodle website and login using an administrator account.

* Click on `Site administration` in the left column menu.

* Click on the `Plugins` tab then click on the `Install plugins` link.<br/>
![img-15](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/15.png)

* Drag and drop the file you downloaded in the previous step to the indicated area in the figure below and click on the `Install plugin from the ZIP file` button.<br/>
![img-16](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/16.png)

* Click on the `Continue` button.<br/>
![img-17](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/17.png)

* If a screen for the plugin’s information or verification appears, just click the `Continue` button at the bottom of the page.

Now that you've installed the plugin, you need to configure it to establish a connection between the iScholar and Moodle systems. To do this, follow the steps below:

* Go to your iScholar system and go to the `Administração` » `Configurações` menu.<br/>
![img-18](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/18.png)

* Click on the `Gerenciar tokens` button.<br/>
![img-19](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/19.png)

* The `Gerenciamento de tokens` page will opens. Click on the `+ cadastrar` button if there is no valid token to the `Sincronização iScholar <> Moodle` in the list.<br/>
![img-20](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/20.png)

* To create a new token, select the `Sincronização iScholar <> Moodle` on `Integração` field and optionally set an expiration date for this token. Click on the `Salvar` button.<br/>
![img-21](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/21.png)

* Once the token is generated, you will be redirected to the previous page. Click on the icon shown in the figure below to copy the token you created.<br/>
![img-22](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/22.png)

* Back to the Moodle, on the `Site administration` / `Plugins`. Click the `iScholar <> Moodle Synchronization` plugin link to access its settings.<br/>
![img-23](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/23.png)

* In the plugin settings page that will open (see the figure below), check the `Enabled` option, enter your iScholar `School code`, paste the copied token into the text box of the `Token from iScholar` field and click `Save changes`.<br/>
![img-24](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/24.png)

By following the above procedure the plugin will automatically configure both Moodle and iScholar systems. To check the configuration status and ensure its correctness, simply access this plugin configuration page again. At the bottom of the page you should see something like the following figure:
![img-25](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/25.png)

If any of the items in the `Configuration status` has failed, click on the `Fix settings` button that will appear right below the last item. The plugin should automatically fix configuration-related failures. If the problem persists, verify that the token provided is valid, and if not, replace it with a valid token and click `Save changes`. If the fault is time zone related, set Moodle's time zones (both the default time zone and the user's time zone) to the same option used in your iScholar. 

## Disabling the integration

To disable the `iScholar <> Moodle Synchronization` integration, go to the plugin settings page in Moodle and uncheck the `Enabled` option, as shown in the figure below then click the `Save changes` button. This procedure will disable the integration in both Moodle and iScholar.<br/>
![img-26](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/26.png)

Note that this procedure will not uninstall the plugin but only disable it. To uninstall, go to `Site administration` / `Plugins` / `Plugins overview`, search for the plugin and click `Uninstall`. Remember to disable the plugin before uninstalling it.

## Mapping iScholar subjects to Moodle courses

Once the installation and activation procedures described above are finished, it will be necessary to carry out the subject-course mapping indicating which iScholar subjects correspond to which courses in Moodle. Keep in mind that data synchronization (manual or automatic) will only send information from teachers, students, groups and enrollments related to previously mapped courses.  

Also note that, since Moodle allows for a free organization of courses and that different schools may opt for different ways to organize their Moodle courses, categories and subcategories, we at iScholar have decided not to impose a strict organization on courses in Moodle and, therefore, the client is responsible for the registration, organization and maintenance of such courses in Moodle. Thus, to carry out the mapping of iScholar disciplines, it is necessary that the courses have already been created in Moodle. 

To map the iScholar subjects, proceed as described below in your iScholar:

* Go to the `Coordenação` » `Grades curriculares` menu.<br/>
![img-27](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/27.png)

* Click on the edit icon of the chosen curriculum.<br/>
![img-28](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/28.png)

* For each subject in the curriculum that has a corresponding course in Moodle, click on the subject's edit icon, as shown in the figure below.<br/>
![img-29](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/29.png)

* At the bottom of the editing form, select the `Curso Moodle` option corresponding to the subject and, optionally, the start and end dates on `Data de inicio` and `Data de encerramento` fields, respectively. Click on the `Salvar` button.<br/>
![img-30](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/30.png)

## Other settings

Finally, there are two other optional configurations available in your iScholar: 

* specify classes that should not be synchronized (in the class edit page).<br/>
![img-31](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/31.png)

* suspend a student (in the student registration editing page).<br/>
![img-32](https://github.com/SistemaiScholar/moodle-tool_ischolarsync/blob/main/docs/32.png)

Keep in mind that marking a class to not to sync with Moodle will have the effect of closing all class-related student and teacher enrollments for the corresponding Moodle courses. In addition, teachers and students affected by the change will have their accounts suspended in Moodle if they are not related to any other classes synchronized with Moodle. 

Also note that suspending a student through the student registration edit form will have the effect of preventing that student's access to Moodle, but will keep their enrollments in the Moodle courses. While a student is suspended, their data will not be synced with Moodle.
