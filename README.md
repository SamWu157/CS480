# CS480 Blockchain Application

Poll Creator using Blockchain API

#### Requirements Analysis

### Project Requirements
* Utilize a block-chain platform to achieve functionality

### Application Requirements
#### Shall Requirements
* Create a new poll
* Find existing polls to vote for
* Submit voting option selections to the poll
* Polls are valid only for a certain length of time
* Poll results are immutable once the deadline passes

#### Should Requirements
* User details are stored along with user's voting selections
* Poll results include statistics and other desirable user metadata'

### Execution of Steps
1. [Poll Creator] creates a new poll
2. [Poll Creator] inserts poll details
3. Application publishes poll
4. (Substep) User details are verified (extends)
5. [Poll Voter] finds a poll
6. [Poll Voter] selects voting choices
7. (Substep) Voting selection transaction is verified by [Blockchain API] (includes)
8. [Blockchain API] adds vote selection to the blockchain
9. Poll expires and triggers generation of poll results
10. (Substep) Poll result analysis and statistics are generated (extends)

### Frontend Layout
* Blockchain Poll Homepage -> index.html
    * Create -> creator/index.html
        * Submit Page -> creator/submit.php
    * Vote -> voter/index.php
        * Poll Page -> voter/poll.php
            * Submit Page -> voter/submit.php
        * Delete Page -> voter/delete.php
    * Results -> results/index.php
        * Results Page **To Do**
        * Delete Page -> results/delete.php
    * Team -> about/index.html

### Setting up MySQL
* root  
```sql
CREATE USER 'cs480'@'localhost';  
GRANT INSERT,CREATE,DELETE,DROP on * . * TO 'cs480'@'localhost';
```
* cs480  
```sql
CREATE DATABASE polls;
CREATE TABLE creators (id TEXT, poll TEXT);
```
