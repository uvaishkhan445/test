from flask import Flask
from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.triggers.cron import CronTrigger
import atexit

app = Flask(__name__)


@app.route("/")
def index():
    return "Hello, World!"


def scheduled_task():
    print("This task runs every minute.")


def setup_scheduler():
    scheduler = BackgroundScheduler()
    # Add a cron job to run the scheduled_task function every minute
    scheduler.add_job(scheduled_task, CronTrigger.from_crontab("* * * * *"))
    scheduler.start()
    # Shut down the scheduler when exiting the app
    atexit.register(lambda: scheduler.shutdown())


if __name__ == "__main__":
    setup_scheduler()
    app.run(debug=True)
