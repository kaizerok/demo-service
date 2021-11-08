#!/bin/bash
if [ -f ".env_secret" ]; then
  crudini --merge .env < .env_secret
fi

eval $ENTRYPOINT_COMMAND
